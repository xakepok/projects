<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelContracts extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`c`.`dat`',  '`c`.`dat`',
                '`project`',  '`project`',
                '`manager`',  '`manager`',
                '`group`',  '`group`',
                '`plan`',  '`plan`',
                '`plan_dat`',  '`plan_dat`',
                '`c`.`number`',  '`c`.`number`',
                '`e`.`title_ru_short`',  '`e`.`title_ru_short`',
                '`c`.`state`',  '`c`.`state`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, DATE_FORMAT(`c`.`dat`,'%d.%m.%Y') as `dat`, `c`.`number`, `c`.`status`, `c`.`currency`, `c`.`amount`, `c`.`state`")
            ->select("`p`.`title` as `project`, `p`.`id` as `projectID`")
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`id` as `exponentID`")
            ->select("`u`.`name` as `manager`, (SELECT MIN(`dat`) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0) as `plan_dat`")
            ->select("`g`.`title` as `group`, (SELECT COUNT(*) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0) as `plan`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_projects` AS `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `expID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`")
            ->leftJoin("`#__usergroups` as `g` ON `g`.`id` = `p`.`groupID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`e`.`title_ru_full` LIKE ' . $search . 'OR `e`.`title_ru_short` LIKE ' . $search . 'OR `e`.`title_en` LIKE ' . $search . 'OR `p`.`title` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`c`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`c`.`state` = 0 OR `c`.`state` = 1)');
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int)$project);
        }
        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor)) {
            $query->where('`c`.`expID` = ' . (int)$exhibitor);
        }
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`p`.`managerID` = ' . (int)$manager);
        }
        // Фильтруем по статусу.
        $status = $this->getState('filter.status');
        if (is_numeric($status)) {
            if ($status != -1) {
                $query->where('`c`.`status` = ' . (int)$status);
            }
            else {
                $query->where('`c`.`status` IS NULL');
            }
        }
        /* Фильтр по ID проекта (только через GET) */
        $id = JFactory::getApplication()->input->getInt('id', 0);
        if ($id != 0)
        {
            $query->where("`c`.`id` = {$id}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`plan_dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array(), 'amount' => array('rub' => 0, 'usd' => 0, 'eur' => 0));
        $ids = array();
        $format = JFactory::getApplication()->input->getString('format', 'html');
        foreach ($items as $item) {
            $ids[] = $item->id;
            $arr['id'] = $item->id;
            $arr['dat'] = $item->dat;
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}");
            $arr['project'] = ($format != 'html') ? $item->project : JHtml::link($url, $item->project);
            $arr['currency'] = $item->currency;
            $url = JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->id}");
            if ($format == 'html') $arr['edit_link'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_GO'));
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;filter_contract={$item->id}");
            $link = JHtml::link($url, JText::sprintf('COM_PROJECTS_HEAD_TODO_TODOS'));
            if ($format == 'html') $arr['todo'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->exponentID}");
            $exponentName = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $exponentUrl = JHtml::link($url, $exponentName);
            $arr['exponent'] = ($format != 'html') ? $exponentName : $exponentUrl;
            $arr['number'] = $item->number;
            $arr['manager']['title'] = $item->manager ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MANAGER_UNDEFINED');
            $arr['manager']['class'] = (!empty($item->manager)) ? '' : 'no-data';
            $arr['group']['title'] = $item->group ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PROJECT_GROUP_UNDEFINED');
            $arr['group']['class'] = (!empty($item->group)) ? '' : 'no-data';
            $arr['plan'] = $item->plan;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            //$amount = $item->amount;
            $amount = $this->getAmount($item);
            $arr['amount'] = ($format != 'html') ? $amount : sprintf("%s %s", $amount, $item->currency);
            $arr['amount_only'] = $amount; //Только цена
            //$arr['debt'] = ($format != 'html') ? $amount - $this->getDebt($item->id) : sprintf("%s %s", $amount - $this->getDebt($item->id), $item->currency);
            $arr['state'] = $item->state;
            $result['items'][] = $arr;
            $result['amount'][$item->currency] += $amount;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor');
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.project', $project);
        $this->setState('filter.exhibitor', $exhibitor);
        $this->setState('filter.manager', $manager);
        $this->setState('filter.manager', $status);
        $this->setState('filter.state', $published);
        parent::populateState('`plan_dat`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.status');
        $id .= ':' . $this->getState('filter.state');
        return parent::getStoreId($id);
    }

    /**
     * Расчёт стоимости договора
     * @param object $item   объект со сделкой
     * @return float
     * @since 1.2.0
     */
    private function getAmount(object $item): float
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("ROUND(SUM(`i`.`price_{$item->currency}`*`v`.`value`*(CASE WHEN `v`.`columnID`='1' THEN `i`.`column_1` WHEN `v`.`columnID`='2' THEN `i`.`column_2` WHEN `v`.`columnID`='3' THEN `i`.`column_3` END)*IFNULL(`v`.`markup`,1)*IFNULL(`v`.`factor`,1)*IFNULL(`v`.`value2`,1)), 2) as `amount`")
            ->from("`#__prj_contract_items` as `v`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `v`.`itemID`")
            ->where("`v`.`contractID` = {$item->id}");
        return (float) 0 + $db->setQuery($query)->loadResult();
    }

    /**
     * @param int  $contract_id ID сделки
     * @return float
     * @since 1.2.4
     */
    private function getDebt(int $contract_id): float
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(SUM(`amount`),0)")
            ->from("`#__prj_scores`")
            ->where("`contractID` = {$contract_id}")
            ->where("`state` = 1");
        return (float) $db->setQuery($query)->loadResult();
    }

}