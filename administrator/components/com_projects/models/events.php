<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelEvents extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'manager',
                'dat', 'a.dat',
                'section',
                'action',
                'search',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`a`.`id`, `a`.`section`, `a`.`action`, `a`.`itemID`')
            ->select('`a`.`old_data`, `a`.`params`')
            ->select("DATE_FORMAT(`a`.`dat`,'%d.%m.%Y %H:%i') as `dat`")
            ->select("`u`.`name` as `manager`")
            ->from("`#__prj_user_action_log` as `a`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `a`.`userID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('(`a`.`params` LIKE ' . $search . ' OR `a`.`old_data` LIKE ' . $search . ')');
        }

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering', 'a.dat');
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
            $arr['dat'] = $item->dat;
            $arr['section'] = ProjectsHelper::getEventSection($item->section);
            $arr['action'] = ProjectsHelper::getEventAction($item->action);
            $arr['manager'] = $item->manager;
            $arr['itemID'] = $item->itemID;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('a.dat', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}