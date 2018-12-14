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
                '`p`.`title_ru`',
                '`section`',
                '`project`',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`prj`.`title_ru` as `project`, `c`.`number` as `contract`, `c`.`currency`, `c`.`id` as `id`')
            ->select("`p`.`column_1`, `p`.`column_2`, `p`.`column_3`")
            ->select("`p`.`id` as `itemID`, `p`.`title_ru` as `item`, `p`.`unit`, IFNULL(`p`.`unit_2`,'TWO_NOT_USE') as `unit_2`, `p`.`price_rub`, `p`.`price_usd`, `p`.`price_eur`")
            ->select('`i`.`columnID`, `i`.`value`, `i`.`factor`, `i`.`markup`, `i`.`value2`')
            ->select("`s`.`id` as `sectionID`, `s`.`title` as `section`")
            ->from("`#__prj_contract_items` as `i`")
            ->leftJoin("`#__prc_items` as `p` ON `p`.`id` = `i`.`itemID`")
            ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `p`.`sectionID`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `i`.`contractID`")
            ->leftJoin("`#__prj_projects` as `prj` ON `prj`.`id` = `c`.`prjID`")
            ->where("`c`.`status` = 1")
            ->where("`p`.`in_stat` = 1");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`p`.`title_ru` LIKE ' . $search);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int)$project);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`p`.`title_ru`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $result['amount'] = array('rub' => 0, 'usd' => 0, 'eur' => 0);;
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=item.edit&amp;&id={$item->itemID}");
            $link = JHtml::link($url, $item->item);
            $arr['title'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;task=section.edit&amp;&id={$item->sectionID}");
            $link = JHtml::link($url, $item->section);
            $arr['section'] = $link;
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['unit_2'] = ProjectsHelper::getUnit($item->unit_2);
            $currency = "price_".$item->currency;
            $arr['value'] = $item->value;
            $arr['price'][$item->currency] = sprintf("%s %s", $item->$currency, $item->currency);
            if (!isset($result['items'][$item->itemID]))
            {
                $result['items'][$item->itemID]['amount'] = array('rub' => 0, 'usd' => 0, 'eur' => 0);
                $result['items'][$item->itemID] = $arr;
            }
            $result['items'][$item->itemID]['amount'][$item->currency] += $this->getAmount($item);
            $result['amount'][$item->currency] += $this->getAmount($item);
        }
        return $result;
    }

    /**
     * Возвращает сумму по конкретному пункту в договоре
     * @param object $item
     * @return float
     * @since 1.0.3.2
     */
    private function getAmount(object $item): float
    {
        $columnID = "column_{$item->columnID}";
        $currency = "price_".$item->currency;
        $amount = $item->$currency * $item->$columnID * $item->value;
        if ($item->factor) $amount *= $item->factor;
        if ($item->markup) $amount *= $item->markup;
        if ($item->value2) $amount *= $item->value2;
        return $amount;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.search', $search);
        $this->setState('filter.project', $project);
        parent::populateState('`p`.`title_ru`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        return parent::getStoreId($id);
    }
}