<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelBuilding extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'title_ru_short',
                'contract',
                'freeze',
                'search',
                'project',
                'sq',
                'manager',
                'standtype',
                'standstate',
                'standstatus',
                's.status',
                'exp_status',
                'exhibitor',
                'stand',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`s`.`id` as `standID`, `cat`.`number` as `stand`, `s`.`freeze`, IFNULL(`s`.`tip`,0) as `tip`, IFNULL(`s`.`status`,1) as `status`, `cat`.`square`as `sq`')
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`id` as `exponentID`")
            ->select("IFNULL(`c`.`number_free`,`c`.`number`) as `contract`, `c`.`id` as `contractID`, `c`.`status` as `exp_status`")
            ->select("`u`.`name` as `manager`")
            ->select("(SELECT getStandPavilion(`cat`.`number`)) as `pavilion`")
            ->from("`#__prj_catalog` as `cat`")
            ->leftJoin("`#__prj_stands` as `s` ON `s`.`catalogID` = `cat`.`id`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`");
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`cat`.`number` LIKE ' . $search);
        }
        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor)) {
            $query->where('`c`.`expID` = ' . (int)$exhibitor);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (empty($project)) $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $catalog = ProjectsHelper::getProjectCatalog($project);
            $query->where('`cat`.`titleID` = ' . (int)$catalog);
        }
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`c`.`managerID` = ' . (int)$manager);
        }
        // Фильтруем по типу стенда.
        $standtype = $this->getState('filter.standtype');
        if (is_numeric($standtype)) {
            $query->where('`s`.`tip` = ' . (int)$standtype);
        }
        // Фильтруем по текущему состоянию стенда стенда.
        $standstate = $this->getState('filter.standstate');
        if (is_numeric($standstate)) {
            $query->where('`s`.`status` = ' . (int)$standstate);
        }
        // Фильтруем по статусу стенда.
        $standstatus = $this->getState('filter.standstatus');
        if (is_numeric($standstatus)) {
            if ($standstatus == 0) $query->where('`e`.`id` IS NULL');
            if ($standstatus == 1) $query->where('`e`.`id` IS NOT NULL');
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 'pavilion, stand');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems($raw = false)
    {
        $items = parent::getItems();
        $results = array();
        $stands = array(); //Массив контракт - массив стендов
        $itog = array(); //Итоговый массив
        $return = base64_encode("index.php?option=com_projects&view=building");
        foreach ($items as $item) {
            $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->exponentID}&amp;return={$return}");
            $exhibitor = ($item->exponentID != null) ? ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en) : '';
            $link = JHtml::link($url, $exhibitor);
            $arr['exhibitor'] = (!$item->sq == null) ?  $link : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $url = JRoute::_("index.php?option=com_projects&amp;task=stand.edit&amp;contractID={$item->contractID}&amp;id={$item->standID}&amp;return={$return}");
            $arr['stand'] = (!$item->contractID == null) ? JHtml::link($url, $item->stand) : $item->stand;
            $title = sprintf("%s (%s, %s)", $item->stand, ProjectsHelper::getStandType($item->tip), ProjectsHelper::getStandStatus($item->status));
            $stands[$item->contractID][] = JHtml::link($url, $title);
            $number = (!empty($item->contract)) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_DOGOVOR_N', $item->contract) : JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
            $arr['contract'] = (!$item->exponentID == null) ? JHtml::link($url,$number) : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['sq'] = (!$item->sq == null) ? sprintf("%s %s", $item->sq , JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM')) : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['contractID'] = (!$item->exponentID == null) ? $item->contractID : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['manager'] = (!$item->exponentID == null) ? $item->manager : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['status'] = (!$item->exponentID == null) ? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_BUSY') : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE_OK');
            $arr['exp_status'] = (!$item->exponentID == null) ? ProjectsHelper::getExpStatus($item->exp_status) : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['freeze'] = $item->freeze ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            $arr['tip'] = (!$item->exponentID == null) ? ProjectsHelper::getStandType($item->tip) : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            if (ProjectsHelper::canDo('core.general')) {
                $arr['scheme'] = (!$item->exponentID == null) ? ProjectsHelper::getStandStatus($item->status) : JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE');
            }
            $arr['pavilion'] = $item->pavilion;
            if (!isset($arr['square'][$item->pavilion])) $arr['square'][$item->pavilion] = 0;
            $arr['square'][$item->pavilion] += $item->sq;
            $results[] = $arr;
        }

        return $results;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor');
        $this->setState('filter.exhibitor', $exhibitor);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.project', $project);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $this->setState('filter.manager', $manager);
        $standtype = $this->getUserStateFromRequest($this->context . '.filter.standtype', 'filter_standtype');
        $this->setState('filter.standtype', $standtype);
        $standstate = $this->getUserStateFromRequest($this->context . '.filter.standstate', 'filter_standstate');
        $this->setState('filter.standstate', $standstate);
        $standstatus = $this->getUserStateFromRequest($this->context . '.filter.standstatus', 'filter_standstatus');
        $this->setState('filter.standstatus', $standstatus);
        parent::populateState('pavilion, stand', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.standtype');
        $id .= ':' . $this->getState('filter.standstatus');
        return parent::getStoreId($id);
    }
}