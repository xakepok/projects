<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelHotelcats extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'title',
                'hotel',
                'search',
            );
        }
        parent::__construct($config);
    }

    public static function getInstance($type='Hotelcats', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getInstance($type, $prefix, $config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, `c`.`hotelID`, IFNULL(`c`.`title_ru`,`c`.`title_en`) as `title`, IFNULL(`h`.`title_ru`,`h`.`title_en`) as `hotel`")
            ->from("`#__prj_hotels_number_categories` as `c`")
            ->leftJoin("`#__prj_hotels` as `h` ON `h`.`id` = `c`.`hotelID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('(`c`.`title_ru` LIKE ' . $search . ' OR `c`.`title_en` LIKE ' . $search . ')');
        }
        //Фильтр по отелю
        $hotel = $this->getState('filter.hotel');
        if (is_numeric($hotel)) {
            $query->where('`c`.`hotelID` = ' . (int) $hotel);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 'title');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $return = base64_encode("index.php?option=com_projects&view=hotelcats");
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=hotelcat.edit&amp;id={$item->id}");
            $link = JHtml::link($url, $item->title);
            $arr['title'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;task=hotel.edit&amp;id={$item->hotelID}&amp;return={$return}");
            $link = JHtml::link($url, $item->hotel);
            $arr['hotel'] = $link;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $hotel = $this->getUserStateFromRequest($this->context . '.filter.hotel', 'filter_hotel');
        $this->setState('filter.hotel', $hotel);
        parent::populateState('title', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.hotel');
        return parent::getStoreId($id);
    }
}