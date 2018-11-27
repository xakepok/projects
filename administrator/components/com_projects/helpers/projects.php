<?php

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class ProjectsHelper
{
    public function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS'), 'index.php?option=com_projects&amp;view=projects', $vName == 'projects');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CONTRACTS'), 'index.php?option=com_projects&amp;view=contracts', $vName == 'contracts');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_TODOS'), 'index.php?option=com_projects&amp;view=todos', $vName == 'todos');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_EXHIBITORS'), 'index.php?option=com_projects&amp;view=exhibitors', $vName == 'exhibitors');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SCORES'), 'index.php?option=com_projects&amp;view=scores', $vName == 'scores');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PAYMENTS'), 'index.php?option=com_projects&amp;view=payments', $vName == 'payments');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PRICES'), 'index.php?option=com_projects&amp;view=prices', $vName == 'prices');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SECTIONS'), 'index.php?option=com_projects&amp;view=sections', $vName == 'sections');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ITEMS'), 'index.php?option=com_projects&amp;view=items', $vName == 'items');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PLANS'), 'index.php?option=com_projects&amp;view=plans', $vName == 'plans');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), 'index.php?option=com_projects&amp;view=activities', $vName == 'activities');
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
     * Возвращает текстовый статус задачи из планировщика
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
     * @return int
     * @since 1.2.2
     */
    public static function getContractNumber(): int
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(MAX(`number`)+1,1)")
            ->from("`#__prj_contracts`")
            ->where("`number` IS NOT NULL");
        return $db->setQuery($query)->loadResult();
    }

    /**
     * Возвращает список стендов из указанной сделки
     * @param int $contractID
     * @return array
     * @since 1.3.0.2
     */
    public function getContractStands(int $contractID): array
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_stands` as `s`")
            ->where("`s`.`contractID` = {$contractID}");
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
            ->order("`t`.`state` asc");
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Проверяет возможность использовать указанный номер в договоре
     * @param   int $number   Номер договора
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
        return $title_ru_short ?? $title_ru_full ?? $title_en;
    }

    /**
     * Возвращает статус участия экспонента в проекте.
     * @param string|null $status Значение из таблицы.
     * @return string
     * @since 1.1.9
     */
    public static function getExpStatus($status): string
    {
        if ($status == null) return JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS_UNDEFINED');
        $status = mb_strtoupper($status);
        return JText::sprintf("COM_PROJECTS_HEAD_CONTRACT_STATUS_{$status}");
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
