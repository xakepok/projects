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
                '`title`', '`title`',
                '`date_start`', '`date_start`',
                '`date_end`', '`date_end`',
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
            ->select("`t`.`id`, DATE_FORMAT(`t`.`dat`,'%d.%m.%Y') as `date`, `t`.`task`, `t`.`result`, `t`.`state`")
            ->select("`c`.`id` as `contract`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->select("`u1`.`name` as `open`, `u2`.`name` as `close`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`")
            ->from("`#__prj_todos` as `t`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `t`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__users` as `u1` ON `u1`.`id` = `t`.`userOpen`")
            ->leftJoin("`#__users` as `u2` ON `u2`.`id` = `t`.`userClose`");

        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`t`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('`t`.`state` = 0');
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

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`t`.`dat');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->contract}");
            $link = JHtml::link($url, $item->contract);
            $arr['contract'] = $link;
            $arr['project'] = $item->project;
            $arr['exp'] = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $url = JRoute::_("index.php?option=com_projects&amp;view=todo&amp;layout=edit&amp;id={$item->id}");
            $link = JHtml::link($url, $item->date);
            $arr['dat'] = $link;
            $arr['task'] = $item->task;
            $arr['result'] = $item->result;
            $arr['open'] = $item->open;
            $arr['close'] = $item->close;
            $arr['state'] = $item->state;
            $arr['state_text'] = ProjectsHelper::getTodoState($item->state);
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
}