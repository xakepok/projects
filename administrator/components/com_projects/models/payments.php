<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelPayments extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`pm`.`pp`','`pm`.`pp`',
                '`pm`.`dat`','`pm`.`dat`',
                '`number`','`number`',
                '`title_ru_short`','`title_ru_short`',
                '`author`','`author`',
                '`project`','`project`',
                '`state`', '`state`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`pm`.`id`, `pm`.`pp`, `pm`.`amount`, `u`.`name` as `author`")
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`id` as `exponentID`")
            ->select("`p`.`title` as `project`, `p`.`id` as `projectID`")
            ->select("`c`.`id` as `contractID`, `c`.`number`, `c`.`currency`")
            ->select("DATE_FORMAT(`pm`.`dat`,'%d.%m.%Y') as `dat`")
            ->from("`#__prj_payments` as `pm`")
            ->leftJoin("`#__prj_scores` as `s` ON `s`.`id` = `pm`.`scoreID`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `pm`.`created_by`");

        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`pm`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`pm`.`state` = 0 OR `pm`.`state` = 1)');
        }
        // Фильтруем по сделке.
        $contract = $this->getState('filter.contract');
        if (is_numeric($contract))
        {
            $query->where('`s`.`contractID` = ' . (int) $contract);
        }
        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor))
        {
            $query->where('`c`.`expID` = ' . (int) $exhibitor);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (is_numeric($project))
        {
            $query->where('`c`.`prjID` = ' . (int) $project);
        }
        // Фильтруем по счёту.
        $score = $this->getState('filter.score');
        if (is_numeric($score))
        {
            $query->where('`pm`.`scoreID` = ' . (int) $score);
        }
        //Если не руководитель, выводим только свои платежи
        if (!ProjectsHelper::canDo('projects.exec.edit'))
        {
            $user = JFactory::getUser();
            $query->where("`pm`.`created_by` = {$user->id}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`pm`.`dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Возвращает сумму сделанных платежей по указанному счёту
     * @param int $scoreID
     * @return float
     * @since 1.3.0.9
     */
    public function getScorePayments(int $scoreID): float
    {
        if ($scoreID == 0) return 0;
        $scoreID = $this->_db->escape($scoreID);
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(SUM(`amount`),0)")
            ->from("`#__prj_payments`")
            ->where("`scoreID` = {$scoreID}");
        return $db->setQuery($query)->loadResult();
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $exponentName = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr = array();
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;view=payment&amp;layout=edit&amp;id={$item->id}");
            $arr['pp'] = JHtml::link($url, $item->pp);
            $arr['dat'] = $item->dat;
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->exponentID}");
            $arr['exp'] = JHtml::link($url, $exponentName);
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}");
            $arr['project'] = JHtml::link($url, $item->project);
            $url = JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->contractID}");
            $arr['contract'] = JHtml::link($url, $item->number);
            $arr['amount'] = number_format($item->amount, 2, '.', "'")."&nbsp;".$item->currency;
            $arr['author'] = $item->author;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $contract = $this->getUserStateFromRequest($this->context . '.filter.contract', 'filter_contract', '', 'string');
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor', '', 'string');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project', '', 'string');
        $score = $this->getUserStateFromRequest($this->context . '.filter.score', 'filter_score', '', 'string');
        $this->setState('filter.state', $published);
        $this->setState('filter.contract', $contract);
        $this->setState('filter.exhibitor', $exhibitor);
        $this->setState('filter.project', $project);
        $this->setState('filter.score', $score);
        parent::populateState('`pm`.`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.contract');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.score');
        return parent::getStoreId($id);
    }
}