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
                'item',
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
            ->select("`cat`.`id`, `cat`.`number`, `cat`.`square`, `cat`.`itemID`")
            ->select("`i`.`title_ru` as `item`, `i`.`unit`")
            ->from("`#__prj_catalog` as `cat`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `cat`.`itemID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`cat`.`number` LIKE ' . $search);
        }
        // Фильтруем по пункту прайса.
        $item = $this->getState('filter.item');
        if (is_numeric($item))
        {
            $query->where('`cat`.`itemID` = ' . (int) $item);
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
        $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=contracts");
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=catalog.edit&amp;&id={$item->id}");
            $link = JHtml::link($url, $item->number);
            $arr['number'] = (!ProjectsHelper::canDo('core.general')) ? $item->price : $link;
            $url = JRoute::_("index.php?option=com_projects&amp;task=item.edit&amp;&id={$item->itemID}&amp;return={$return}");
            $link = JHtml::link($url, $item->item);
            $title = $item->item ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NOT_ASSET');
            $arr['item'] = (!ProjectsHelper::canDo('core.general') || $item->item == null) ? $title : $link;
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
        $item = $this->getUserStateFromRequest($this->context . '.filter.item', 'filter_item', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.item', $item);
        parent::populateState('`cat`.`number`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.item');
        return parent::getStoreId($id);
    }
}