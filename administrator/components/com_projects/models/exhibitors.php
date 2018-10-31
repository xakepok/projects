<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelExhibitors extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`id`', '`e`.`id`',
                '`title_ru_short`', '`title_ru_short`',
                '`title_ru_full`', '`title_ru_full`',
                '`title_en`', '`title_en`',
                '`state`', '`e`.`state`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`state`')
            ->select("`r`.`name` as `city`")
            ->select("`u`.`name` as `manager`")
            ->from("`#__prj_exp` as `e`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `e`.`curatorID`")
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`title_ru_full` LIKE ' . $search . 'OR `title_ru_short` LIKE ' . $search . 'OR `title_en` LIKE ' . $search);
        }
        // Фильтруем по состоянию.
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('`e`.`state` = ' . (int)$published);
        } elseif ($published === '') {
            $query->where('(`e`.`state` = 0 OR `e`.`state` = 1)');
        }

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering', '`title_ru_short`');
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
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->id}");
            $link = JHtml::link($url, ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en));
            $arr['region'] = $item->city;
            $arr['manager'] = $item->manager;
            $arr['title'] = $link;
            $arr['state'] = $item->state;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $published);
        parent::populateState('`title_ru_short`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        return parent::getStoreId($id);
    }
}