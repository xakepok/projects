<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelTodos extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`t`.`dat`','`t`.`dat`',
                '`t`.`dat_open`','`t`.`dat_open`',
                '`t`.`dat_close`','`t`.`dat_close`',
                '`open`','`open`',
                '`close`','`close`',
                '`manager`','`manager`',
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
            ->select("`t`.`id`, `t`.`task`, `t`.`result`, `t`.`state`")
            ->select("DATE_FORMAT(`t`.`dat`,'%d.%m.%Y') as `date`")
            ->select("DATE_FORMAT(`t`.`dat_open`,'%d.%m.%Y %k:%i') as `dat_open`, DATE_FORMAT(`t`.`dat_close`,'%d.%m.%Y %k:%i') as `dat_close`")
            ->select("`c`.`id` as `contract`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->select("`u1`.`name` as `open`, `u2`.`name` as `close`, `u3`.`name` as `manager`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`")
            ->from("`#__prj_todos` as `t`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `t`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__users` as `u1` ON `u1`.`id` = `t`.`userOpen`")
            ->leftJoin("`#__users` as `u2` ON `u2`.`id` = `t`.`userClose`")
            ->leftJoin("`#__users` as `u3` ON `u3`.`id` = `t`.`managerID`");

        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`t`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`t`.`state` = 0 OR `t`.`state` = 1)');
        }
        // Фильтруем по сделке.
        $contract = $this->getState('filter.contract');
        if (is_numeric($contract))
        {
            $query->where('`t`.`contractID` = ' . (int) $contract);
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
        //Если не руководитель, выводим только назначенные пользователю задания
        if (!ProjectsHelper::canDo('projects.exec.edit'))
        {
            $user = JFactory::getUser();
            $query->where("`t`.`managerID` = {$user->id}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`t`.`dat`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result_no_expire = array();
        $result_expire = array();
        foreach ($items as $item) {
            $arr['expired'] = $this->isExpired($item->date, $item->state);
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->contract}");
            $link = JHtml::link($url, $item->contract);
            $arr['contract'] = $link;
            $arr['project'] = $item->project;
            $arr['exp'] = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $url = JRoute::_("index.php?option=com_projects&amp;view=todo&amp;layout=edit&amp;id={$item->id}");
            $link = JHtml::link($url, $item->date);
            $arr['dat'] = $link;
            $arr['dat_open'] = $item->dat_open;
            $arr['dat_close'] = $item->dat_close ?? JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE_NOT_CLOSE');
            $arr['task'] = $item->task;
            $arr['result'] = ($arr['expired']) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE_EXPIRED') : $item->result;
            $arr['open'] = $item->open;
            $arr['manager'] = $item->manager;
            $arr['close'] = ($arr['expired']) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE_EXPIRED') : $item->close;
            $arr['state'] = $item->state;
            $arr['state_text'] = ($arr['expired']) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE_EXPIRED') : ProjectsHelper::getTodoState($item->state);
            if (!$arr['expired']) $result_no_expire[] = $arr;
            if ($arr['expired']) $result_expire[] = $arr;
        }
        return array_merge($result_expire, $result_no_expire);
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $contract = $this->getUserStateFromRequest($this->context . '.filter.contract', 'filter_contract', '', 'string');
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor', '', 'string');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project', '', 'string');
        $this->setState('filter.state', $published);
        $this->setState('filter.contract', $contract);
        $this->setState('filter.exhibitor', $exhibitor);
        $this->setState('filter.project', $project);
        parent::populateState('`t`.`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.contract');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        return parent::getStoreId($id);
    }

    /**
     * Просрочено ли задание
     * @param string $dat Дата, в которую должно быть выполнено задание
     * @param int $state Состояние задания
     * @return bool
     * @since 1.2.6
     */
    private function isExpired(string $dat, int $state): bool
    {
        $date_item = new DateTime();
        $date_item->setDate(date("Y", strtotime($dat)), date("m", strtotime($dat)), date("d", strtotime($dat)));
        $date_item->setTime(23,59,59);
        $date_now = new DateTime();
        return ($date_now > $date_item && $state == '0') ? true : false;
    }
}