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
                't.dat',
                't.dat_open',
                't.dat_close',
                'open',
                'manager',
                'project',
                'exhibitor',
                'e.title_ru_short',
                'c.number',
                'state',
                'dat',
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
            ->select("DATE_FORMAT(`t`.`dat_open`,'%d.%m.%Y') as `dat_open`, DATE_FORMAT(`t`.`dat_close`,'%d.%m.%Y') as `dat_close`")
            ->select("`c`.`id` as `contract`, `c`.`number`, `c`.`status` as `contract_status`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`, `e`.`id` as `expID`")
            ->select("`u1`.`name` as `open`, `u3`.`name` as `manager`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`, `p`.`id` as `projectID`")
            ->from("`#__prj_todos` as `t`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `t`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__users` as `u1` ON `u1`.`id` = `t`.`userOpen`")
            ->leftJoin("`#__users` as `u3` ON `u3`.`id` = `t`.`managerID`");
        /* Фильтр */
        $contractID = JFactory::getApplication()->input->getInt('contractID', 0);
        $search = $this->getState('filter.search');
        if (!empty($search) && $contractID == 0)
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`e`.`title_ru_full` LIKE ' . $search . 'OR `e`.`title_ru_short` LIKE ' . $search . 'OR `e`.`title_en` LIKE ' . $search . 'OR `p`.`title_ru` LIKE ' . $search);
        }
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
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`t`.`managerID` = ' . (int)$manager);
        }
        // Фильтруем по дате.
        $dat = $this->getState('filter.dat');
        if (!empty($dat))
        {
            $dat = $this->_db->quote($this->_db->escape($dat));
            $query->where('`t`.`dat` = ' . $dat);
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
        //Фильтруем по дате из URL
        $dat = JFactory::getApplication()->input->getString('date');
        if ($dat !== null)
        {
            $dat = $this->_db->quote($this->_db->escape($dat));
            $query->where("`t`.`dat` = {$dat}");
        }
        //Если не руководитель, выводим только назначенные пользователю задания
        if (!ProjectsHelper::canDo('core.general'))
        {
            $user = JFactory::getUser();
            $query->where("`t`.`managerID` = {$user->id}");
        }
        if ($contractID !== 0)
        {
            $query->where("`t`.`contractID` = {$contractID}");
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            //Фильтруем по менеджеру из URL
            $man = JFactory::getApplication()->input->getInt('uid', 0);
            if ($man !== 0)
            {
                $query->where("`t`.`managerID` = {$man}");
            }
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
        $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=todos");
        foreach ($items as $item) {
            $arr['expired'] = $this->isExpired($item->date, $item->state);
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contract}&amp;return={$return}");
            $c = (!$item->number) ? ProjectsHelper::getExpStatus($item->contract_status) : JText::sprintf('COM_PROJECTS_HEAD_TODO_DOGOVOR_N', $item->number);
            $link = JHtml::link($url, $c);
            $arr['contract'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$item->projectID}&amp;return={$return}");
            $arr['project'] = (!ProjectsHelper::canDo('core.general')) ? $item->project : JHtml::link($url, $item->project);
            $exhibitor = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->expID}&amp;return={$return}");
            $arr['exp'] = JHtml::link($url, $exhibitor);
            $url = JRoute::_("index.php?option=com_projects&amp;task=todo.edit&amp;id={$item->id}");
            $link = JHtml::link($url, $item->date);
            $arr['dat'] = $link;
            $arr['dat_open'] = $item->dat_open;
            $arr['expID'] = $item->expID;
            $arr['dat_close'] = $item->dat_close ?? JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE_NOT_CLOSE');
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$item->contract}");
            $arr['task'] = JHtml::link($url, $item->task);
            $arr['result'] = ($arr['expired']) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE_EXPIRED') : $item->result;
            $arr['open'] = $item->open;
            $arr['manager'] = $item->manager;
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
        $this->setState('filter.state', $published);
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string');
        $this->setState('filter.search', $search);
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor', '', 'string');
        $this->setState('filter.exhibitor', $exhibitor);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project', '', 'string');
        $this->setState('filter.project', $project);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager', '', 'string');
        $this->setState('filter.manager', $manager);
        $dat = $this->getUserStateFromRequest($this->context . '.filter.dat', 'filter_dat', '', 'string');
        $this->setState('filter.dat', $dat);
        parent::populateState('`t`.`dat`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.dat');
        return parent::getStoreId($id);
    }

    /**
     * Просрочено ли задание
     * @param string $dat Дата, в которую должно быть выполнено задание
     * @param int $state Состояние задания
     * @return bool
     * @throws
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