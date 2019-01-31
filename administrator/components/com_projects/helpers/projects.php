<?php

use Joomla\CMS\Language\Text;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\File;

defined('_JEXEC') or die;

class ProjectsHelper
{
    public function addSubmenu($vName)
    {
        $view = JFactory::getApplication()->input->getString('view');
        $notify = self::getNotifies();
        if ($notify > 0) {
            JHtmlSidebar::addEntry(Text::sprintf('COM_PROJECTS_MENU_NOTIFY', $notify), 'index.php?option=com_projects&amp;view=todos&amp;notify=1', $vName == 'todos');
        }
        if (in_array($view, array('reports', 'contracts', 'todos', 'building', 'stat', 'scores', 'payments', 'catalogs', 'cattitles', 'exhibitors', 'prices', 'sections', 'items'))) {
            JHtmlSidebar::addFilter(JText::_('COM_PROJECTS_FILTER_SELECT_ACTIVE_PROJECT'), 'set_active_project', JHtml::_('select.options', ProjectsHtmlFilters::projectOptions(), 'value', 'text', self::getActiveProject()));
        }
        if (self::canDo('core.general')) {
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS'), 'index.php?option=com_projects&amp;view=projects', $vName == 'projects');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PLANS'), 'index.php?option=com_projects&amp;view=plans', $vName == 'plans');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CATTITLES'), 'index.php?option=com_projects&amp;view=cattitles', $vName == 'cattitles');
        }
        if (self::canDo('core.manager') || self::canDo('core.accountant')) {
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CONTRACTS'), 'index.php?option=com_projects&amp;view=contracts', $vName == 'contracts');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_TODOS'), 'index.php?option=com_projects&amp;view=todos', $vName == 'todos');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_EXHIBITORS'), 'index.php?option=com_projects&amp;view=exhibitors', $vName == 'exhibitors');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_BUILDING'), 'index.php?option=com_projects&amp;view=building', $vName == 'building');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_TEMPLATES'), 'index.php?option=com_projects&amp;view=templates', $vName == 'templates');
        }
        if (self::canDo('core.accountant')) {
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SCORES'), 'index.php?option=com_projects&amp;view=scores', $vName == 'scores');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PAYMENTS'), 'index.php?option=com_projects&amp;view=payments', $vName == 'payments');
        }
        if (self::canDo('core.general')) {
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CATALOG'), 'index.php?option=com_projects&amp;view=catalogs', $vName == 'catalogs');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_STAT'), 'index.php?option=com_projects&amp;view=stat', $vName == 'stat');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_REPORTS'), 'index.php?option=com_projects&amp;view=reports', $vName == 'reports');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PRICES'), 'index.php?option=com_projects&amp;view=prices', $vName == 'prices');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SECTIONS'), 'index.php?option=com_projects&amp;view=sections', $vName == 'sections');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ITEMS'), 'index.php?option=com_projects&amp;view=items', $vName == 'items');
            JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), 'index.php?option=com_projects&amp;view=activities', $vName == 'activities');
        }
    }

    /**
     * Возвращает массив с типами текущих отчётов
     * @return array
     * @since 1.1.0.6
     */
    public static function getReportTypes(): array
    {
        return array('exhibitors' => 'Отчёт по экспонентам');
    }

    /**
     * Возвращает название текущего отчёта
     * @param string $type Тип отчёта
     * @return string
     * @since 1.1.0.6
     */
    public static function getReportType(string $type): string
    {
        $types = self::getReportTypes();
        return $types[$type];
    }

    /**
     * Возвращает текстовое представление адреса
     * @param array $data Массив с параметрами адреса в нужном порядке
     * @return string
     * @since 1.1.0.6
     */
    public static function buildAddress(array $data): string
    {
        foreach ($data as $i => $value) {
            if (empty($value)) unset($data[$i]);
        }
        return implode(", ", $data);
    }

    /**
     * Возвращает павильон, которому принадлежит стенд в соответствии с возвращённым из базы кодом
     * @param string $code код павильона
     * @return string
     * @since 1.1.0.4
     */
    public static function getStandPavilion(string $code): string
    {
        $tbl = array(0 => $code, 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F');
        $pavilion = "";
        if (strlen($code) == 1 && is_numeric($code)) {
            $pavilion = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_PAVILION_N', $tbl[intval($code)]);
        }
        if (strlen($code) == 1 && !is_numeric($code))
        {
            if ($code == 'V')
            {
                $pavilion = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_CLOSE');
            }
            if ($code == 'Z')
            {
                $pavilion = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_STREET_CLOSE');
            }
        }
        if (strlen($code) > 1)
        {
            $pavilion = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_PAVILION_STREET', $tbl[intval(substr($code,1,1))]);
        }

        return $pavilion;
    }

    /**
     * Возвращает количество непрочитанных уведомлений
     * @since 1.0.9.3
     * @return int
     */
    public static function getNotifies(): int
    {
        $db =& JFactory::getDbo();
        $userID = JFactory::getUser()->id;
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(COUNT(`id`),0)")
            ->from("`#__prj_todos`")
            ->where("`is_notify` = 1")
            ->where("`managerID` = {$userID}")
            ->where("`state` = 0");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает активный проект из глобальной настройки
     * @param string $default возвращаемое значение по умолчанию, если проект не выбран
     * @return mixed
     * @since 1.0.8.9
     */
    public static function getActiveProject(string $default = '')
    {
        $session = JFactory::getSession();
        $project = $session->get('active_project', '');
        return ($project != 0) ? $project : $default;
    }

    /**
     * Возвращает массив со списком ID сделок, у которых номер стенда указан вручную
     * @return array
     * @since 1.0.6.0
     * @deprecated
     * Не используется с 1.0.8.6
     */
    public static function getStandHandNumber(): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("DISTINCT `contractID`")
            ->from("`#__prj_stands`")
            ->where("`catalogID` IS NULL")
            ->where("`itemID` IS NULL");
        return $db->setQuery($query)->loadColumn() ?? array();
    }

    /**
     * Возвращает массив с площадями стендов по типам у указанной сделки
     * @param int $contractID ID сделки
     * @return array
     * @since 1.0.6.4
     */
    public static function getStandsSquare(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`itemID`, SUM(`sq`) as `sq`")
            ->from("`#__prj_contract_stands`")
            ->group("`itemID`")
            ->where("`contractID` = {$contractID}");
        return $db->setQuery($query)->loadObjectList('itemID') ?? array();
    }

    /**
     * Загружает файл из формы в указанную директорию
     * @param string $field название поля из глобального массива $_FILES
     * @param string $folder наименование директории, куда загружать файл
     * @param int $id ID элемента
     * @return string имя загруженного файла
     * @since 1.0.4.2
     */
    public static function uploadFile(string $field, string $folder, int $id): string
    {
        $path = JPATH_ROOT . "/images/{$folder}/{$id}";
        $name = File::makeSafe($_FILES['jform']['name'][$field]);
        $tmp = $_FILES['jform']['tmp_name'][$field];
        Folder::create($path);
        if (!empty($tmp) && !empty($name)) {
            if (File::upload($tmp, $path . "/" . $name)) {
                chmod($path . "/" . $name, 0777);
            }
        }
        return $name;
    }

    /**
     * Возвращает сумму договоров по проекту
     * @param int $projectID ID проекта
     * @param array $statuses массив со статусами сделок, которые учитываются в договоре
     * @return array массив с 3 валютами
     * @since 1.0.4.3
     */
    public static function getProjectAmount(int $projectID, array $statuses = array(1, 2, 3, 4)): array
    {
        $statuses = implode(', ', $statuses);
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(SUM(`a`.`amount_rub`),0) as `rub`, IFNULL(SUM(`a`.`amount_usd`),0) as `usd`, IFNULL(SUM(`a`.`amount_eur`),0) as `eur`")
            ->from("`#__prj_contract_amounts` as `a`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `a`.`contractID`")
            ->where("`c`.`prjID` = {$projectID}")
            ->where("`c`.`status` IN ({$statuses})");
        if (!ProjectsHelper::canDo('projects.contracts.totalamount')) {
            $userID = JFactory::getUser()->id;
            $query->where("`c`.`managerID` = {$userID}");
        }
        $result = $db->setQuery($query)->loadAssocList();
        return $result[0];
    }

    /**
     * Возвращает сумму всех платежей по проекту
     * @param int $projectID ID проекта
     * @param array $statuses массив со статусами сделок, которые учитываются в договоре
     * @return array сумма всех платежей в трёх валютах
     * @since 1.0.4.3
     */
    public static function getProjectPayments(int $projectID, array $statuses = array(1, 2, 3, 4)): array
    {
        $statuses = implode(', ', $statuses);
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`p`.`payments`, `c`.`currency`")
            ->from("`#__prj_contract_payments` as `p`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `p`.`contractID`")
            ->where("`c`.`prjID` = {$projectID}")
            ->where("`c`.`status` IN ({$statuses})");
        if (!ProjectsHelper::canDo('projects.contracts.totalamount')) {
            $userID = JFactory::getUser()->id;
            $query->where("`c`.`managerID` = {$userID}");
        }
        $payments = $db->setQuery($query)->loadObjectList();
        $result = array('rub' => 0, 'usd' => 0, 'eur' => 0);
        foreach ($payments as $payment) {
            $result[$payment->currency] += $payment->payments;
        }
        return $result;
    }

    /**
     * Возвращает сумму договора
     * @param int $contractID ID договора
     * @return float сумма договора
     * @since 1.0.4.0
     */
    public static function getContractAmount(int $contractID): float
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $currency = self::getContractCurrency($contractID);
        $query
            ->select("`amount_{$currency}`")
            ->from("`#__prj_contract_amounts`")
            ->where("`contractID` = {$contractID}");

        return (float)0 + $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает массив каталога со стендами
     * @param int $contractID ID сделки
     * @since 1.0.5.5
     * @return array массив со стендами
     */
    public static function getCatalogStands(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id` as `standID`, `number`, `square`-IFNULL((SELECT SUM(`value`) FROM `#__prj_contract_items` WHERE `contractID` = {$contractID} AND `catalogID` = `standID`),0) as `sq`")
            ->from("`#__prj_catalog`")
            ->order("`number`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = sprintf("№%s (%s %s)", $item->number, $item->sq, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM'));
            $options[] = JHtml::_('select.option', $item->standID, $title);
        }

        return $options;
    }

    /**
     * Возвращает сумму вместе с валютой
     * @param float $amount Сумма
     * @param string $currency Валюта
     * @return string
     * @since 1.0.8.6
     */
    public static function getCurrency(float $amount, string $currency): string
    {
        return sprintf("%s %s", number_format($amount, '2', ',', ' '), JText::sprintf("COM_PROJECTS_HEAD_ITEM_PRICE_SMALL_" . mb_strtoupper($currency)));
    }

    /**
     * Возвращает валюту сделки
     * @param int $contractID ID сделки
     * @return string
     * @since 1.0.4.0
     */
    public function getContractCurrency(int $contractID): string
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`currency`")
            ->from("`#__prj_contracts`")
            ->where("`id` = {$contractID}");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает ID проекта, на который заключена сделка
     * @param int $contractID ID сделки
     * @return int ID проекта
     * #@since 1.0.4.5
     */
    public static function getContractProject(int $contractID): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`prjID`")
            ->from("`#__prj_contracts`")
            ->where("`id` = {$contractID}");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает сделки, которые участвуют в проекте
     * @param int $projectID ID проекта
     * @return array массив со сделками
     * @since 1.0.3.5
     */
    public static function getProjectContracts(int $projectID): array
    {
        $result = array();
        if ($projectID == 0) return $result;
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id` as `contractID`, `c`.`number`, `c`.`status`")
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`id` as `exponentID`")
            ->select("`u`.`name` as `manager`, (SELECT COUNT(*) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0) as `plan_cnt`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `expID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`")
            ->where("`c`.`prjID` = {$projectID}")
            ->order("`plan_cnt` DESC");
        $items = $db->setQuery($query)->loadObjectList();
        $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=project&layout=edit&id={$projectID}");
        foreach ($items as $item) {
            $arr = array();
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
            $contract = self::getExpStatus($item->status);
            if (!empty($item->number)) $contract = sprintf("%s №%s", $contract, $item->number);
            $arr['contract'] = JHtml::link($url, $contract);
            $arr['manager'] = $item->manager;
            $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->exponentID}&amp;return={$return}");
            $exhibitor = self::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr['exhibitor'] = JHtml::link($url, $exhibitor);
            $arr['plan_cnt'] = $item->plan_cnt;
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Возвращает массив, ключ - ID счёта, значение - сумма платежей по нему
     * @param array $scoreIDs Массив с ID сделок
     * @return array
     * @since 1.3.0.8
     */
    public static function getPaymentSum(array $scoreIDs): array
    {
        $result = array();
        if (empty($scoreIDs)) return $result;
        $ids = implode(', ', $scoreIDs);
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `scoreID`, `amount`")
            ->from("`#__prj_payments`")
            ->where("`scoreID` IN ({$ids})");
        $payments = $db->setQuery($query)->loadObjectList();
        foreach ($payments as $payment) {
            if ($result[$payment->scoreID]) $result[$payment->scoreID] = (float)0;
            $result[$payment->scoreID] += $payment->amount;
        }
        return $result;
    }

    /**
     * Возвращает активную колонку прайса для указанной сделки
     * @param int $contractID ID контракта
     * @return int ID колонки
     * @since 1.2.9.5
     */
    public static function getActivePriceColumn(int $contractID): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(`p`.`columnID`,1) as `column`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->where("`c`.`id` = {$contractID}");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает ID каталога со стендами для указанного проекта
     * @param int $projectID
     * @return int
     * @since 1.0.7.1
     */
    public static function getProjectCatalog(int $projectID): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`catalogID`")
            ->from("`#__prj_projects`")
            ->where("`id` = {$projectID}");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает ID прайс-листа для указанного проекта
     * @param int $projectID
     * @return int
     * @since 1.0.5.2
     */
    public static function getProjectPrice(int $projectID): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(`priceID`,0)")
            ->from("`#__prj_projects`")
            ->where("`id` = {$projectID}");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает текстовый статус счёта
     * @param int $state
     * @return string
     * @since 1.2.3
     */
    public static function getScoreState(int $state): string
    {
        return JText::sprintf("COM_PROJECTS_HEAD_SCORE_STATE_{$state}");
    }

    /**
     * Возвращает тип шаблона
     * @param int $tip
     * @return string
     * @since 1.1.0.1
     */
    public static function getTemplateType(int $tip = 0): string
    {
        return JText::sprintf("COM_PROJECTS_HEAD_TMPL_TYPE_{$tip}");
    }

    /**     * Возвращает текстовый статус задачи из планировщика
     * @param int $state
     * @return string
     * @since 1.2.3
     */
    public static function getTodoState(int $state): string
    {
        return JText::sprintf("COM_PROJECTS_HEAD_TODO_STATE_{$state}");
    }

    /**
     * Возвращает дополнительную информацию о планируемом платеже
     * @param int $scoreID ID счёта
     * @return object
     * @since 1.3.0.8
     */
    public static function getPaymentAdvInfo(int $scoreID): object
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`number` as `score`, `c`.`number` as `contract`")
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`")
            ->from("`#__prj_scores` as `s`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->where("`s`.`id` = {$scoreID}");
        return $db->setQuery($query)->loadObject();
    }

    /**
     * Возвращает следующий по очереди номер договора
     * @param int $projectID ID сделки
     * @return int
     * @since 1.0.9.8
     */
    public static function getContractNumber(int $projectID): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(MAX(`number`)+1,1)")
            ->from("`#__prj_contracts`")
            ->where("`prjID` = {$projectID}")
            ->where("`number` IS NOT NULL");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает счета текущей сделки
     * @param int $contractID
     * @return array
     * @since 1.0.3.6
     */
    public static function getContractScores(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, DATE_FORMAT(`dat`,'%d.%m.%Y') as `dat`, `number`, `amount`, `state`")
            ->from("`#__prj_scores`")
            ->where("`contractID` = {$contractID}");
        return $db->setQuery($query)->loadObjectList() ?? array();
    }

    /**
     * Возвращает платежи по счетам для текущей сделки
     * @param int $contractID
     * @return array
     * @since 1.0.3.6
     */
    public static function getContractPayments(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("DATE_FORMAT(`p`.`dat`,'%d.%m.%Y') as `dat`, `p`.`scoreID`, `p`.`pp`, `p`.`amount`")
            ->from("`#__prj_payments` as `p`")
            ->leftJoin("`#__prj_scores` as `s` ON `s`.`id` = `p`.`scoreID`")
            ->where("`s`.`contractID` = {$contractID}");
        $items = $db->setQuery($query)->loadObjectList();
        $result = array();
        foreach ($items as $item) {

            if (!isset($result[$item->scoreID])) $result[$item->scoreID] = array();
            $result[$item->scoreID][] = $item;
        }
        return $result;
    }

    /**
     * Возвращает список стендов из указанной сделки
     * @param int $contractID
     * @return array
     * @since 1.3.0.2
     */
    public static function getContractStands(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`tip`, `c`.`square` as `sq`, `s`.`freeze`, `s`.`comment`, `s`.`status`, `s`.`scheme`, `s`.`itemID`")
            ->select("`c`.`number`")
            ->select("`i`.`title_ru` as `item`")
            ->from("`#__prj_stands` as `s`")
            ->leftJoin("`#__prj_catalog` as `c` ON `c`.`id` = `s`.`catalogID`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `s`.`itemID`")
            ->where("`s`.`contractID` = {$contractID}");
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Возвращает массив с контактными лицами для указанного экспонента
     * @param int $exbID ID экспонента
     * @return array
     * @since 1.3.0.9
     */
    public static function getExhibitorPersons(int $exbID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_exp_persons` as `p`")
            ->where("`p`.`exbID` = {$exbID}");
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Возвращает список заданий из планировщика для указанной сделки
     * @param int $contractID
     * @return array
     * @since 1.2.9.7
     */
    public static function getContractTodo(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`t`.`id`, DATE_FORMAT(`t`.`dat`,'%d.%m.%Y') as `dat`, `u`.`name` as `user`, `t`.`task`, `t`.`result`, `t`.`state`")
            ->select("IF(`t`.`dat`<CURRENT_DATE(),IF(`t`.`state`=0,'1','0'),'0') as `expired`")
            ->from("`#__prj_todos` as `t`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = IF(`t`.`state`!=0,`t`.`userClose`,`t`.`managerID`)")
            ->where("`t`.`contractID` = {$contractID}")
            ->where("`t`.`is_notify` != 1")
            ->order("`t`.`state` asc");
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Проверяет возможность использовать указанный номер в договоре
     * @param   int $number Номер договора
     * @return boolean
     * @since 1.2.2
     */
    public static function checkContractNumber(int $number): bool
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("COUNT(`id`)")
            ->from("`#__prj_contracts`")
            ->where("`number` = {$number}");
        return ($db->setQuery($query)->loadResult() != 0) ? false : true;
    }

    /**
     * Возвращает ID экспонентов по указанному виду деятельности
     * @param int $id ID вида деятальности
     * @return array
     * @since 1.2.0
     */
    public static function getExponentsInActivities(int $id): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`exbID`')
            ->from("`#__prj_exp_act`")
            ->where("`actID` = {$id}");
        $items = $db->setQuery($query)->loadObjectList();
        $result = array();
        if (!empty($items)) {
            foreach ($items as $item) {
                $result[] = $item->exbID;
            }
        }
        return $result;
    }

    /**
     * Возвращает название экспонента в зависимости от заполненных данных.
     * @param string|null $title_ru_short Короткое название по-русски.
     * @param string|null $title_ru_full Полное название по-русски.
     * @param string|null $title_en Название по-английски.
     * @return string
     * @since 1.1.9
     */
    public static function getExpTitle($title_ru_short = null, $title_ru_full = null, $title_en = null): string
    {
        $title = $title_ru_short ?? $title_ru_full ?? $title_en;
        return $title ?? JText::sprintf('COM_PROJECTS_ERROR_NOT_GET_EXP_TITLE');
    }

    /**
     * Возвращает статус участия экспонента в проекте.
     * @param string|null $status Значение из таблицы.
     * @param string $isCoExp Является соэкспонентом
     * @return string
     * @since 1.1.0.6
     */
    public static function getExpStatus($status, $isCoExp = null): string
    {
        if ($status == null) return JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS_UNDEFINED');
        $status = mb_strtoupper($status);
        if ($isCoExp != null && $status == '0') {
            return JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS_5');
        }
        return JText::sprintf("COM_PROJECTS_HEAD_CONTRACT_STATUS_{$status}");
    }

    public static function getApplication(string $app): string
    {
        $app = strtoupper($app);
        return JText::sprintf("COM_PROJECTS_HEAD_ITEM_APPLICATION_{$app}");
    }

    /**
     * @param int $id ID типа стенда
     * @return string
     * @since 1.3.0.2
     */
    public static function getStandType(int $id): string
    {
        return JText::sprintf("COM_PROJECTS_HEAD_CONTRACT_STAND_TYPE_{$id}");
    }

    /**
     * @param int $id ID типа стенда
     * @return string
     * @since 1.3.0.2
     */
    public static function getStandStatus(int $id): string
    {
        return JText::sprintf("COM_PROJECTS_HEAD_CONTRACT_STAND_STATUS_{$id}");
    }

    /**
     * Возвращает русское значение единицы измерения пункта прайса.
     * @param string $name Значение из таблицы.
     * @return string
     * @since 1.1.4
     */
    public static function getUnit(string $name): string
    {
        $name = mb_strtoupper($name);
        try {
            $text = JText::sprintf("COM_PROJECTS_HEAD_ITEM_UNIT_{$name}");
        } catch (Exception $exception) {
            $text = JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_UNKNOWN');
        }
        return $text;
    }

    /**
     * Возвращает права доступа текущего пользователя
     * @param string $action требуемое право для проверки доступа
     * @return bool
     * @since 1.2.5
     */
    public static function canDo(string $action): bool
    {
        return JFactory::getUser()->authorise($action, 'com_projects');
    }
}
