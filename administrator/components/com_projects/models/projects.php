<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelProjects extends ListModel
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
                '`manager`', '`manager`',
                '`group`', '`group`',
                '`price`', '`price`',
                '`column`', '`column`',
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
            ->select('`p`.`id`, `p`.`title`, `p`.`columnID` as `column`')
            ->select('`pr`.`title` as `price`')
            ->select("DATE_FORMAT(`p`.`date_start`,'%d.%m.%Y') as `date_start`")
            ->select("DATE_FORMAT(`p`.`date_end`,'%d.%m.%Y') as `date_end`")
            ->from("`#__prj_projects` `p`")
            ->select("`u`.`name` as `manager`")
            ->select("`g`.`title` as `group`")
            ->leftJoin("`#__prc_prices` as `pr` ON `pr`.`id` = `p`.`priceID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `p`.`managerID`")
            ->leftJoin("`#__usergroups` as `g` ON `g`.`id` = `p`.`groupID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`p`.`title` LIKE ' . $search);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`p`.`title`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->id}");
            $link = JHtml::link($url, $item->title);
            $arr['title'] = $link;
            $arr['date_start'] = $item->date_start;
            $arr['date_end'] = $item->date_end;
            $arr['manager'] = $item->manager;
            $arr['price'] = $item->price;
            $arr['column'] = $item->column;
            $arr['group'] = $item->group;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('`p`.`title`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}