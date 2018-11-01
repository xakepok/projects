<?php

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class ProjectsHelper
{
    public function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS'), 'index.php?option=com_projects&amp;view=projects', $vName == 'projects');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CONTRACTS'), 'index.php?option=com_projects&amp;view=contracts', $vName == 'contracts');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_EXHIBITORS'), 'index.php?option=com_projects&amp;view=exhibitors', $vName == 'exhibitors');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PRICES'), 'index.php?option=com_projects&amp;view=prices', $vName == 'prices');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SECTIONS'), 'index.php?option=com_projects&amp;view=sections', $vName == 'sections');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ITEMS'), 'index.php?option=com_projects&amp;view=items', $vName == 'items');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PLANS'), 'index.php?option=com_projects&amp;view=plans', $vName == 'plans');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), 'index.php?option=com_projects&amp;view=activities', $vName == 'activities');
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
}
