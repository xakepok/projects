<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelStat extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                '`id`', '`id`',
                '`title`', '`title`'
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`prj`.`title` as `project`, `c`.`number` as `contract`')
            ->select('`p`.`title_ru` as `item`, `p`.`price_rub`, `p`.`price_usd`, `p`.`price_eur`')
            ->select('`i`.`columnID`, `i`.`value`, `i`.`factor`, `i`.`markup`, `i`.`value2`')
            ->from($db->quoteName("`#__prj_contract_items` as `i`"))
            ->leftJoin("`#__prc_items` as `p` ON `p`.`id` = `i`.`itemID`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `i`.`contractID`")
            ->leftJoin("`#__prj_projects` as `prj` ON `prj`.`id` = `c`.`prjID`")
            ->where("`c`.`status` = 1");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`title` LIKE ' . $search);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`item`');
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
            $url = JRoute::_("index.php?option=com_projects&amp;view=price&amp;layout=edit&amp;&id={$item->id}");
            $link = JHtml::link($url, $item->title);
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
        $this->setState('filter.search', $search);
        parent::populateState('`item`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}