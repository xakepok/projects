<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelCattitles extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'title',
                'search',
                'tip',
            );
        }
        parent::__construct($config);
    }

    public static function getInstance($type='Cattitles', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getInstance($type, $prefix, $config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`cat`.`id`, `cat`.`title`, `cat`.`tip`")
            ->from("`#__prj_catalog_titles` as `cat`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`cat`.`title` LIKE ' . $search);
        }
        //Фильтр по глобальному проекту
        $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $cid = ProjectsHelper::getProjectCatalog($project);
            $query->where('`cat`.`id` = ' . (int) $cid);
        }
        //Фильтр по глобальному проекту
        $tip = $this->getState('filter.tip');
        if (is_numeric($tip)) {
            $query->where('`cat`.`tip` = ' . (int) $tip);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`cat`.`title`');
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
            $url = JRoute::_("index.php?option=com_projects&amp;task=cattitle.edit&amp;&id={$item->id}");
            $link = JHtml::link($url, $item->title);
            $arr['title'] = (!ProjectsHelper::canDo('core.general')) ? $item->price : $link;
            $arr['tip'] = ProjectsHelper::getCatalogType($item->tip);
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $tip = $this->getUserStateFromRequest($this->context . '.filter.tip', 'filter_tip');
        $this->setState('filter.tip', $tip);
        parent::populateState('`cat`.`title`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.tip');
        return parent::getStoreId($id);
    }
}