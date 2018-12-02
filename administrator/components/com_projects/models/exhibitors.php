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
                '`city`', '`city`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`')
            ->select("`r`.`name` as `city`")
            ->from("`#__prj_exp` as `e`")
            ->leftJoin("`#__prj_exp_bank` as `b` ON `b`.`exbID` = `e`.`id`")
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('(`title_ru_full` LIKE ' . $search . 'OR `title_ru_short` LIKE ' . $search . 'OR `title_en` LIKE ' . $search . ')');
        }
        // Фильтруем по городу.
        $city = $this->getState('filter.city');
        if (is_numeric($city) && $format != 'html') {
            $query->where('`e`.`regID` = ' . (int)$city);
        }
        // Фильтруем по видам деятельности.
        $act = $this->getState('filter.activity');
        if (is_numeric($act)) {
            $exponents = ProjectsHelper::getExponentsInActivities($act);
            if (!empty($exponents)) {
                $exponents = implode(', ', $exponents);
                $query->where("`e`.`id` IN ({$exponents})");
            }
            else
            {
                $query->where("`e`.`id` IN (-1)");
            }
        }
        // Фильтруем по ИНН (для поиска синонимов)
        $inn = JFactory::getApplication()->input->getInt('inn', 0);
        if ($inn !== 0)
        {
            $query->where("`b`.`inn` LIKE {$inn}");
        }
        // Фильтруем по названию (для поиска синонимов)
        $text = JFactory::getApplication()->input->getString('text', '');
        $text = $db->quote('%' . $db->escape($text, true) . '%', false);
        if ($text !== '')
        {
            $query->where('(`title_ru_full` LIKE ' . $text . ' OR `title_ru_short` LIKE ' . $text . ' OR `title_en` LIKE ' . $text . ')');
        }

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering', '`title_ru_short`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->id}");
            $link = JHtml::link($url, $title);
            $arr['region'] = $item->city;
            $arr['title'] = ($format != 'html') ? $title : $link;
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $activity = $this->getUserStateFromRequest($this->context . '.filter.activity', 'filter_activity', '', 'string');
        $city = $this->getUserStateFromRequest($this->context . '.filter.city', 'filter_city', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $activity);
        $this->setState('filter.city', $city);
        parent::populateState('`title_ru_short`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.activity');
        $id .= ':' . $this->getState('filter.city');
        return parent::getStoreId($id);
    }
}