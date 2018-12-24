<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelStat extends ListModel
{
    public $itemID;

    public function __construct(array $config)
    {
        $this->itemID = JFactory::getApplication()->input->getInt('itemID', 0);
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'p.title_ru',
                'title_ru_short',
                'search',
                'c.number',
                'project',
                'application',
                'price_rub',
                'price_usd',
                'price_eur',
                'amount_rub',
                'amount_usd',
                'amount_eur',
                'value',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`itemID`, `i`.`title_ru` as `item`, `i`.`application`, `i`.`unit`")
            ->select("SUM(`s`.`value`) as `value`")
            ->select("SUM(`s`.`price_rub`) as `amount_rub`, SUM(`s`.`price_usd`) as `amount_usd`, SUM(`s`.`price_eur`) as `amount_eur`")
            ->select("`i`.`price_rub`, `i`.`price_usd`,`i`.`price_eur`")
            ->select("`c`.`currency`")
            ->from("`#__prj_stat` as `s`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `s`.`itemID`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->where("`i`.`in_stat` = 1");

        if ($this->itemID != 0)
        {
            $query
                ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`, `c`.`expID`")
                ->select("`c`.`number` as `contract`, `c`.`id` as `contractID`")
                ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
                ->where("`s`.`itemID` = {$this->itemID}")
                ->group("`c`.`expID`");
        }
        else
        {
            $query->group("`s`.`itemID`");
        }

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`i`.`title_ru` LIKE ' . $search);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int)$project);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`i`.`title_ru`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $result['amount'] = array('rub' => 0, 'usd' => 0, 'eur' => 0);
        $result['items'] = array();
        foreach ($items as $item) {
            if ($this->itemID != 0)
            {
                $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=stat&itemID={$this->itemID}");
                $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->expID}&amp;return={$return}");
                $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_rn);
                $arr['title'] = JHtml::link($url, $title);
                $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
                $title = ($item->contract != null) ? JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITH_NUMBER', $item->contract) : JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
                $arr['contract'] = JHtml::link($url, $title);
            }
            else
            {
                $url = JRoute::_("index.php?option=com_projects&amp;view=stat&amp;itemID={$item->itemID}");
                $arr['title'] = JHtml::link($url, $item->item);
            }
            $arr['application'] = ProjectsHelper::getApplication($item->application);
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['unit_2'] = '';
            $arr['value'] = $item->value;
            $currency = "price_".$item->currency;
            $arr['price'][$item->currency] = sprintf("%s %s", number_format($item->$currency, 0, ".", " "), $item->currency);
            $currency = "amount_".$item->currency;
            $arr['amount'][$item->currency] = sprintf("%s %s", number_format($item->$currency, 0, ".", " "), $item->currency);
            $result['items'][] = $arr;
        }
        return $result;
    }

    public function getItemID(): int
    {
        return $this->itemID;
    }

    /**
     * Возвращает название пункта прайс-листа
     * @return string
     * @since 1.0.5.0
     */
    public function getExhibitorTitle(): string
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`title_ru`")
            ->from("`#__prc_items`")
            ->where("`id` = {$this->itemID}");
        return $db->setQuery($query)->loadResult();
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.search', $search);
        $this->setState('filter.project', $project);
        parent::populateState('`i`.`title_ru`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        return parent::getStoreId($id);
    }
}