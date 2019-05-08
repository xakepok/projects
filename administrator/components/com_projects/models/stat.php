<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelStat extends ListModel
{
    public $itemID;

    public function __construct(array $config)
    {
        $this->itemID = JFactory::getApplication()->input->getInt('itemID', 0);
        if (empty($config['filter_fields'])) {
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
                'item',
                'status'
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
            ->from("`#__prj_stat_by_currency` as `s`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `s`.`itemID`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->where("`s`.`value` > 0");

        if ($this->itemID != 0) {
            $query
                ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`, `c`.`expID`")
                ->select("IFNULL(`c`.`number_free`,`c`.`number`) as `contract`, `c`.`id` as `contractID`")
                ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
                ->where("`s`.`itemID` = {$this->itemID}")
                ->group("`c`.`expID`");
        }
        if ($this->itemID == 0) {
            $query->group("`s`.`itemID`");
        }

        if (!ProjectsHelper::canDo('projects.access.stat.full')) {
            $uid = JFactory::getUser()->id;
            $query->where("`c`.`managerID` = {$uid}");
        }

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`i`.`title_ru` LIKE ' . $search);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (empty($project)) $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int) $project);
            $session = JFactory::getSession();
            $session->set('projectID', $project);
        }
        // Фильтруем по статусу.
        $status = $this->getState('filter.status');
        if (is_array($status)) {
            if (!empty($status)) {
                $statuses = implode(', ', $status);
                if ($status[0] != '0') {
                    $query->where("`c`.`status` IN ({$statuses})");
                }
                else
                {
                    $this->state->set('filter.status', '');
                    $query->where("`c`.`status` != 0");
                }
            }
        }
        else {
            $query->where("`c`.`status` != 0");
        }

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering', '`i`.`title_ru`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $result['amount'] = array('rub' => 0, 'usd' => 0, 'eur' => 0);
        $result['sum'] = array('rub' => 0, 'usd' => 0, 'eur' => 0);
        if ($this->itemID != 0) $result['cnt'] = 0;
        $result['items'] = array();
        $xls = (JFactory::getApplication()->input->getString('task') == 'exportxls');
        if ($this->itemID != 0) $item_title = $this->getExhibitorTitle();
        foreach ($items as $item) {
            $arr = array();
            if ($this->itemID != 0) {
                $return = base64_encode("index.php?option=com_projects&view=stat&itemID={$this->itemID}");
                $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->expID}&amp;return={$return}");
                $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_rn);
                $arr['title'] = (!$xls) ? JHtml::link($url, $title) : $title;
                $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
                $title = ($item->contract != null) ? JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITH_NUMBER', $item->contract) : JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
                $arr['contract'] = (!$xls) ? JHtml::link($url, $title) : $title;
                $stands = $this->getStands($item->contractID);
                $arr['stands'] = implode(' ', $stands);
                $arr['item_title'] = $item_title;
                $result['cnt'] += $item->value;
            } else {
                $url = JRoute::_("index.php?option=com_projects&amp;view=stat&amp;itemID={$item->itemID}");
                $options = array('class' => 'small');
                $arr['title'] = (!$xls) ? JHtml::link($url, $item->item, $options) : $item->item;
            }
            $arr['application'] = ProjectsHelper::getApplication($item->application);
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['unit_2'] = '';
            $arr['value'] = $item->value;
            $arr['price']['rub'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->price_rub, 'rub') : $item->price_rub;
            $arr['price']['usd'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->price_usd, 'usd') : $item->price_usd;
            $arr['price']['eur'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->price_eur, 'eur') : $item->price_eur;
            $arr['amount']['rub'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->amount_rub, 'rub') : $item->amount_rub;
            $arr['amount']['usd'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->amount_usd, 'usd') : $item->amount_usd;
            $arr['amount']['eur'] = (!$xls) ? ProjectsHelper::getCurrency((float) $item->amount_eur, 'eur') : $item->amount_eur;
            $result['items'][] = $arr;
            $result['amount']['rub'] += $item->amount_rub;
            $result['amount']['usd'] += $item->amount_usd;
            $result['amount']['eur'] += $item->amount_eur;
        }
        return $result;
    }

    private function getStands(int $contractID): array
    {
        $xls = (JFactory::getApplication()->input->getString('task') == 'exportxls');
        $stands = ProjectsHelper::getContractStands($contractID);
        $result = array();
        $return = base64_encode("index.php?option=com_projects&view=stat&itemID={$this->itemID}");
        foreach ($stands as $stand) {
            $url = JRoute::_("index.php?option=com_projects&amp;task=stand.edit&amp;contractID={$contractID}&amp;id={$stand->id}&amp;return={$return}");
            $result[] = (!$xls) ? JHtml::link($url, $stand->number) : $stand->number;
        }
        return $result;
    }

    public function exportToExcel()
    {
        if (is_array($this->state->get('filter.items'))) return;
        $items = $this->getItems();
        $data = $items['items'];
        JLoader::discover('PHPExcel', JPATH_LIBRARIES);
        JLoader::register('PHPExcel', JPATH_LIBRARIES . '/PHPExcel.php');
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle(JText::sprintf('COM_PROJECTS_MENU_STAT'));
        if ($this->itemID != 0) {
            for ($i = 1; $i < count($data) + 1; $i++) {
                for ($j = 0; $j < 12; $j++) {
                    if ($i == 1) {
                        if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE'));
                        if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_CONTRACT_DESC'));
                        if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_BLANK_STANDS'));
                        if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_EXP_DESC'));
                        if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'));
                        if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB_SHORT'));
                        if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD_SHORT'));
                        if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR_SHORT'));
                        if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT'));
                        if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'));
                        if ($j == 10) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'));
                        if ($j == 11) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'));
                    }
                    if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['item_title']);
                    if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['contract']);
                    if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['stands']);
                    if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['title']);
                    if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['unit']);
                    if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['rub'] ?? 0);
                    if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['usd'] ?? 0);
                    if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['eur'] ?? 0);
                    if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['value'] ?? 0);
                    if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['rub'] ?? 0);
                    if ($j == 10) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['usd'] ?? 0);
                    if ($j == 11) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['eur'] ?? 0);
                }
            }
            $sheet->getColumnDimension('A')->setWidth(66);
            $sheet->getColumnDimension('B')->setWidth(21);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(61);
            $sheet->getColumnDimension('E')->setWidth(9);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(15);
            $sheet->getColumnDimension('K')->setWidth(15);
            $sheet->getColumnDimension('L')->setWidth(15);
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('B1')->getFont()->setBold(true);
            $sheet->getStyle('C1')->getFont()->setBold(true);
            $sheet->getStyle('D1')->getFont()->setBold(true);
            $sheet->getStyle('E1')->getFont()->setBold(true);
            $sheet->getStyle('F1')->getFont()->setBold(true);
            $sheet->getStyle('G1')->getFont()->setBold(true);
            $sheet->getStyle('H1')->getFont()->setBold(true);
            $sheet->getStyle('I1')->getFont()->setBold(true);
            $sheet->getStyle('J1')->getFont()->setBold(true);
            $sheet->getStyle('K1')->getFont()->setBold(true);
            $sheet->getStyle('L1')->getFont()->setBold(true);

        }
        if ($this->itemID == 0) {
            for ($i = 1; $i < count($data) + 1; $i++) {
                for ($j = 0; $j < 10; $j++) {
                    if ($i == 1) {
                        if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_APPLICATION'));
                        if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE'));
                        if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'));
                        if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB_SHORT'));
                        if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD_SHORT'));
                        if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR_SHORT'));
                        if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT'));
                        if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'));
                        if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'));
                        if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'));
                    }
                    if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['application']);
                    if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['title']);
                    if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['unit']);
                    if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['rub'] ?? 0);
                    if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['usd'] ?? 0);
                    if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['price']['eur'] ?? 0);
                    if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['value'] ?? 0);
                    if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['rub'] ?? 0);
                    if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['usd'] ?? 0);
                    if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data[$i - 1]['amount']['eur'] ?? 0);
                }
            }
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(66);
            $sheet->getColumnDimension('C')->setWidth(11);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(11);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);
            $sheet->getColumnDimension('J')->setWidth(15);
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('B1')->getFont()->setBold(true);
            $sheet->getStyle('C1')->getFont()->setBold(true);
            $sheet->getStyle('D1')->getFont()->setBold(true);
            $sheet->getStyle('E1')->getFont()->setBold(true);
            $sheet->getStyle('F1')->getFont()->setBold(true);
            $sheet->getStyle('G1')->getFont()->setBold(true);
            $sheet->getStyle('H1')->getFont()->setBold(true);
            $sheet->getStyle('I1')->getFont()->setBold(true);
            $sheet->getStyle('J1')->getFont()->setBold(true);
        }
        $filename = ($this->itemID != 0) ? JFile::makeSafe($this->getExhibitorTitle()) : 'Report';
        $filename = sprintf("%s.xls", $filename);
        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: public");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        $objWriter->save('php://output');
        jexit();
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
        $this->setState('filter.search', $search);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.project', $project);
        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
        $this->setState('filter.status', $status);
        parent::populateState('`i`.`title_ru`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.status');
        return parent::getStoreId($id);
    }
}