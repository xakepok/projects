<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelCatalogs extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'number',
                'square',
                'catalog',
                'cattitle',
                'unit',
                'search',
            );
        }
        parent::__construct($config);
    }

    public static function getInstance($type='Catalogs', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getInstance($type, $prefix, $config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`cat`.`id`, `cat`.`number`, `cat`.`square`")
            ->select("`t`.`title` as `catalog`, `t`.`id` as `catalogID`")
            ->from("`#__prj_catalog` as `cat`")
            ->leftJoin("`#__prj_catalog_titles` as `t` ON `t`.`id` = `cat`.`titleID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`cat`.`number` LIKE ' . $search);
        }
        // Фильтруем по каталогу стендов.
        $cattitle = $this->getState('filter.cattitle');
        if (is_numeric($cattitle)) {
            $query->where('`cat`.`titleID` = ' . (int) $cattitle);
        }
        //Фильтр по глобальному проекту
        $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $cid = ProjectsHelper::getProjectCatalog($project);
            $query->where('`cat`.`titleID` = ' . (int)$cid);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`cat`.`number`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $return = base64_encode("index.php?option=com_projects&view=catalogs");
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=catalog.edit&amp;&id={$item->id}");
            $link = JHtml::link($url, $item->number);
            $arr['number'] = (!ProjectsHelper::canDo('core.general')) ? $item->price : $link;
            $url = JRoute::_("index.php?option=com_projects&amp;task=cattitle.edit&amp;&id={$item->catalogID}&amp;return={$return}");
            $link = JHtml::link($url, $item->catalog);
            $arr['catalog'] = (!ProjectsHelper::canDo('core.general')) ? $item->catalog : $link;
            $title = ($item->unit != null) ? ProjectsHelper::getUnit($item->unit) : '';
            $arr['square'] = sprintf("%s %s", $item->square, $title);
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $cattitle = $this->getUserStateFromRequest($this->context . '.filter.cattitle', 'filter_cattitle');
        $this->setState('filter.cattitle', $cattitle);
        parent::populateState('`cat`.`number`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.cattitle');
        return parent::getStoreId($id);
    }
}