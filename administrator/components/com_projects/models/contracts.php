<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelContracts extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'c.dat',
                'project',
                'manager',
                'exhibitor',
                'title_ru_short',
                'status',
                'search',
                'plan',
                'plan_dat',
                'currency',
                'number', 'dog_number',
                'sort_amount, amount',
                'sort_amount, debt',
                'sort_amount, payments',
                'stand',
                'doc_status',
                'activity',
                'rubric',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, `c`.`dat`, `c`.`doc_status`, IFNULL(`c`.`number_free`,ifnull(`c`.`number`,'')) as `number`, IFNULL(`c`.`number_free`,ifnull(`c`.`number`,'0')) as `dog_number`, `c`.`status`, `c`.`currency`")
            ->select("`p`.`title_ru` as `project`, `p`.`id` as `projectID`")
            ->select('IFNULL(`e`.`title_ru_short`,IFNULL(`e`.`title_ru_full`,`e`.`title_en`)) as `exhibitor`, `e`.`id` as `exhibitorID`')
            ->select("`u`.`name` as `manager`, (SELECT MIN(`dat`) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0) as `plan_dat`")
            ->select("(SELECT COUNT(*) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0 AND `is_notify` = 0) as `plan`")
            ->select("`a`.`price` as `amount`")
            ->select("IF(`c`.`currency`='rub',0,IF(`c`.`currency`='usd',1,2)) as `sort_amount`")
            ->select("`pay`.`payments`")
            ->select("IFNULL(`a`.`price`,0)-IFNULL(`pay`.`payments`,0) as `debt`")
            ->select("IFNULL(`e1`.`title_ru_short`,IFNULL(`e1`.`title_ru_full`,IFNULL(`e1`.`title_en`,''))) as `payer`, `c`.`payerID`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_projects` AS `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `expID`")
            ->leftJoin("`#__prj_exp` as `e1` ON `e1`.`id` = `c`.`payerID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`")
            ->leftJoin("`#__prj_contract_amounts` as `a` ON `a`.`contractID` = `c`.`id`")
            ->leftJoin("`#__prj_contract_payments` as `pay` ON `pay`.`contractID` = `c`.`id`");
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (strpos($search, '№') !== false || strpos($search, '#') !== false) {
                $search = str_ireplace(array("№",'#'), '', $search);
                $search = $db->q($search);
                $query->where("(`c`.`number` LIKE {$search})");
            }
            else {
                $search = $db->q("%{$search}%");
                $query->where("(`e1`.`title_ru_short` LIKE {$search} OR `e1`.`title_ru_full` LIKE {$search} OR `e1`.`title_en` LIKE {$search} OR `e`.`title_ru_short` LIKE {$search} OR `e`.`title_ru_full` LIKE {$search} OR `e`.`title_en` LIKE {$search} OR `e`.`comment` LIKE {$search})");
            }
        }
        // Фильтруем по видам деятельности.
        $act = $this->getState('filter.activity');
        if (is_numeric($act)) {
            $exponents = ProjectsHelper::getExponentsInActivities($act);
            if (!empty($exponents)) {
                $exponents = implode(', ', $exponents);
                $query->where("`c`.`expID` IN ({$exponents})");
            }
        }
        //Фильтруем по тематикам разделов
        $rubric = $this->getState('filter.rubric');
        if (is_numeric($rubric)) {
            if ($rubric != -1) {
                $ids = ProjectsHelper::getRubricContracts($rubric);
                if (!empty($ids)) {
                    $ids = implode(', ', $ids);
                    $query->where("`c`.`id` IN ({$ids})");
                } else {
                    $query->where("`c`.`id` = 0");
                }
            }
            else {
                $ids = ProjectsHelper::getRubricContracts();
                if (!empty($ids)) {
                    $ids = implode(', ', $ids);
                    $query->where("`c`.`id` NOT IN ({$ids})");
                }
            }
        }

        //Показываем только свои сделки, но если только неактивны фильтры по видам деятельности и тематической рубрике
        if (!ProjectsHelper::canDo('projects.access.contracts.full'))
        {
            if (!is_numeric($act) && !is_numeric($rubric)) {
                $userID = JFactory::getUser()->id;
                $query->where("`c`.`managerID` = {$userID}");
            }
        }

        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (empty($project)) $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int)$project);
        }

        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor)) {
            $query->where('`c`.`expID` = ' . (int)$exhibitor);
        }
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`c`.`managerID` = ' . (int)$manager);
        }
        // Фильтруем по статусу присланного договора.
        $doc_status = $this->getState('filter.doc_status');
        if (is_numeric($doc_status)) {
            $query->where('`c`.`doc_status` = ' . (int)$doc_status);
        }
        // Фильтруем по валюте.
        $currency = $this->getState('filter.currency');
        if (!empty($currency))
        {
            $currency = $db->quote('%' . $db->escape($currency, true) . '%', false);
            $query->where("`c`.`currency` LIKE {$currency}");
        }
        // Фильтруем по статусу.
        $status = $this->getState('filter.status');
        if (is_array($status)) {
            if (!empty($status)) {
                $statuses = implode(', ', $status);
                $query->where("`c`.`status` IN ({$statuses})");
            }
            else
            {
                $query->where("`c`.`status` IS NOT NULL");
            }
        }
        /* Фильтр по ID проекта (только через GET) */
        $id = JFactory::getApplication()->input->getInt('id', 0);
        if ($id != 0)
        {
            $query->where("`c`.`id` = {$id}");
        }
        /* Фильтр по ID экспонента и проекту (только через GET) */
        $exhibitor = JFactory::getApplication()->input->getInt('exhibitorID', 0);
        $project = JFactory::getApplication()->input->getInt('projectID', 0);
        if ($exhibitor != 0 && $project != 0)
        {
            $query->where("`c`.`prjID` = {$project} AND `c`.`expID` = {$exhibitor}");
        }
        if ($exhibitor != 0)
        {
            $query->where("`c`.`expID` = {$exhibitor}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 'plan_dat');
        $orderDirn = $this->state->get('list.direction', 'asc');
        if ($orderCol == 'dog_number') {
            if ($orderDirn == 'ASC') $orderCol = 'LENGTH(dog_number), dog_number';
            if ($orderDirn == 'DESC') $orderCol = 'LENGTH(dog_number) desc, dog_number';
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array(), 'amount' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'debt' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'payments' => array('rub' => 0, 'usd' => 0, 'eur' => 0));
        $ids = array();
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $return = ProjectsHelper::getReturnUrl();
        $prefixes = ProjectsHelper::getProjectsPrefix();
        foreach ($items as $item) {
            $ids[] = $item->id;
            $arr['id'] = $item->id;
            $arr['dat'] = ($item->dat != null) ? JDate::getInstance($item->dat)->format("d.m.Y") : '';
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}&amp;return={$return}");
            $arr['project'] = ($format != 'html' || !ProjectsHelper::canDo('projects.access.projects') || $this->isExcel()) ? $item->project : JHtml::link($url, $item->project);
            $arr['currency'] = $item->currency;
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->id}");
            if ($format == 'html') {
                $arr['edit_link'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_GO'), array('title' => "ID: {$item->id}"));
            }
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$item->id}");
            $link = JHtml::link($url, $item->plan);
            if ($format == 'html') $arr['todo'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->exhibitorID}&amp;return={$return}");
            $exponentUrl = JHtml::link($url, $item->exhibitor, array('title' => "ID: {$item->exhibitorID}"));
            $arr['exponent'] = ($format != 'html' || $this->isExcel()) ? $item->exhibitor : $exponentUrl;
            if ($item->payer !== '') {
                $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->payerID}&amp;return={$return}");
                $arr['exponent'] .= sprintf(" (%s %s)", JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PAYER'), JHtml::link($url, $item->payer));
            }
            $arr['number'] = ($item->number != null) ? $prefixes[$item->projectID]['contract_prefix'].$item->number : '';
            $arr['manager'] = $item->manager ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MANAGER_UNDEFINED');
            if ($format == 'html') $arr['plan'] = $link;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            $arr['stand'] = implode(" ", $this->getStandsForContract($item->id));
            $amount = $item->amount;
            $payments = $item->payments;
            $debt = $item->debt;
            $arr['amount'] = ($format != 'html' || $this->isExcel()) ? $amount : ProjectsHelper::getCurrency((float) $amount, (string) $item->currency);
            $arr['amount_only'] = $amount; //Только цена
            $paid = (float) $amount - (float) $debt;
            $arr['paid'] = (!$this->isExcel()) ? ProjectsHelper::getCurrency((float) $paid, (string) $item->currency) : $paid;
            $arr['debt'] = ($format != 'html' || $this->isExcel()) ? $debt : ProjectsHelper::getCurrency((float) $debt, (string) $item->currency);
            $url = JRoute::_("index.php?option=com_projects&amp;task=score.add&amp;contractID={$item->id}&amp;return={$return}");
            $color = ($debt != 0) ? 'red' : 'green';
            $arr['doc_status'] = JText::sprintf("COM_PROJECTS_HEAD_CONTRACT_DOC_STATUS_{$item->doc_status}");
            $arr['color'] = $color;
            if (ProjectsHelper::canDo('projects.access.finanses.full') && $debt != 0 && ($item->status == '1' || $item->status == '10') && !$this->isExcel()) $arr['debt'] = JHtml::link($url, $arr['debt'], array('title' => JText::sprintf('COM_PROJECTS_ACTION_ADD_SCORE'), 'style' => "color: {$color}"));
            if ($format != 'html') $arr['debt'] = $debt;

            $result['items'][] = $arr;
            if ($item->status != 0) {
                $result['amount'][$item->currency] += $amount;
                $result['debt'][$item->currency] += $debt;
                $result['payments'][$item->currency] += $payments;
            }
        }
        $active_project = ProjectsHelper::getActiveProject();
        if (is_numeric($this->state->get('filter.project')) || $active_project != '')
        {
            $project = $this->state->get('filter.project');
            if (empty($project)) $project = (int) $active_project;
            $statuses = $this->state->get('filter.status') ?? array(1, 2, 3, 4, 10);
            $result['amount']['total'] = ProjectsHelper::getProjectAmount($project, $statuses);
            $result['payments']['total'] = ProjectsHelper::getProjectPayments($project, $statuses);
            $result['debt']['total']['rub'] = $result['amount']['total']['rub'] - $result['payments']['total']['rub'];
            $result['debt']['total']['usd'] = $result['amount']['total']['usd'] - $result['payments']['total']['usd'];
            $result['debt']['total']['eur'] = $result['amount']['total']['eur'] - $result['payments']['total']['eur'];
        }
        return $result;
    }

    public function exportToExcel()
    {
        $items = $this->getItems();
        $data = $items;
        JLoader::discover('PHPExcel', JPATH_LIBRARIES);
        JLoader::register('PHPExcel', JPATH_LIBRARIES . '/PHPExcel.php');
        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle(JText::sprintf('COM_PROJECTS_MENU_CONTRACTS'));
        for ($i = 1; $i < count($data['items']) + 1; $i++) {
            for ($j = 0; $j < 11; $j++) {
                if ($i == 1) {
                    if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_NUMBER_SHORT'));
                    if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DATE_DOG'));
                    if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_SHORT'));
                    if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PROJECT'));
                    if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_EXPONENT'));
                    if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MANAGER'));
                    if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS'));
                    if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_CURRENCY'));
                    if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT'));
                    if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_SCORE_PAYMENT'));
                    if ($j == 10) $sheet->setCellValueByColumnAndRow($j, $i, JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DEBT'));
                }
                if ($j == 0) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['number']);
                if ($j == 1) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['dat']);
                if ($j == 2) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['stand']);
                if ($j == 3) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['project']);
                if ($j == 4) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['exponent']);
                if ($j == 5) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['manager']);
                if ($j == 6) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['status']);
                if ($j == 7) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['currency']);
                if ($j == 8) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['amount'] ?? '');
                if ($j == 9) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['paid']);
                if ($j == 10) $sheet->setCellValueByColumnAndRow($j, $i + 1, $data['items'][$i - 1]['debt']);
            }
        }
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(8);
        $sheet->getColumnDimension('I')->setWidth(19);
        $sheet->getColumnDimension('J')->setWidth(19);
        $sheet->getColumnDimension('K')->setWidth(19);
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
        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: public");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Contracts.xls");
        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        $objWriter->save('php://output');
        jexit();
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.project', $project);
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor');
        $this->setState('filter.exhibitor', $exhibitor);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $this->setState('filter.manager', $manager);
        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
        $this->setState('filter.status', $status);
        $currency = $this->getUserStateFromRequest($this->context . '.filter.currency', 'filter_currency');
        $this->setState('filter.currency', $currency);
        $activity = $this->getUserStateFromRequest($this->context . '.filter.activity', 'filter_activity', '', 'string');
        $this->setState('filter.state', $activity);
        $rubric = $this->getUserStateFromRequest($this->context . '.filter.rubric', 'filter_rubric', '', 'string');
        $this->setState('filter.rubric', $rubric);
        $doc_status = $this->getUserStateFromRequest($this->context . '.filter.doc_status', 'filter_doc_status');
        $this->setState('filter.doc_status', $doc_status);
        parent::populateState('plan_dat', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.status');
        $id .= ':' . $this->getState('filter.currency');
        $id .= ':' . $this->getState('filter.activity');
        $id .= ':' . $this->getState('filter.rubric');
        $id .= ':' . $this->getState('filter.doc_status');
        return parent::getStoreId($id);
    }

    /**
     * Возвращает стенды сделки
     * @param int $contractID ID сделки
     * @return array
     * @since 1.0.8.6
     */
    public function getStandsForContract(int $contractID): array
    {
        $stands = ProjectsHelper::getContractStands($contractID);
        $return = base64_encode("index.php?option=com_projects&view=contracts");
        $result = array();
        $tip = ProjectsHelper::getContractType($contractID);
        foreach ($stands as $stand) {
            $url = JRoute::_("index.php?option=com_projects&amp;task=stand.edit&amp;contractID={$stand->contractID}&amp;id={$stand->id}&amp;return={$return}");
            $result[] = (($contractID != $stand->contractID && $tip == 0) || $this->isExcel()) ? $stand->number : JHtml::link($url, ($tip == 0) ? $stand->number : $stand->title);
        }
        return $result;
    }

    /**
     * Расчёт стоимости договора
     * @param object $item   объект со сделкой
     * @return float
     * @since 1.2.0
     */
    public function getAmount(object $item): float
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("ROUND(SUM(`i`.`price_{$item->currency}`*`v`.`value`*(CASE WHEN `v`.`columnID`='1' THEN `i`.`column_1` WHEN `v`.`columnID`='2' THEN `i`.`column_2` WHEN `v`.`columnID`='3' THEN `i`.`column_3` END)*IFNULL(`v`.`markup`,1)*IFNULL(`v`.`factor`,1)*IFNULL(`v`.`value2`,1)), 0) as `amount`")
            ->from("`#__prj_contract_items` as `v`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `v`.`itemID`")
            ->where("`v`.`contractID` = {$item->id}");
        return (float) 0 + $db->setQuery($query)->loadResult();
    }

    public function isExcel(): bool
    {
        $task = JFactory::getApplication()->input->getString('task');
        return ($task != 'exportxls') ? false : true;
    }
}