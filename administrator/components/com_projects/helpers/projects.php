<?php
use Joomla\CMS\Language\Text;
defined('_JEXEC') or die;

class ProjectsHelper
{
	public function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(Text::_('COM_PROJECTS'), 'index.php?option=com_projects&amp;view=projects', $vName == 'projects');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_EXHIBITORS'), 'index.php?option=com_projects&amp;view=exhibitors', $vName == 'exhibitors');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PRICES'), 'index.php?option=com_projects&amp;view=prices', $vName == 'prices');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_SECTIONS'), 'index.php?option=com_projects&amp;view=sections', $vName == 'sections');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ITEMS'), 'index.php?option=com_projects&amp;view=items', $vName == 'items');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PLANS'), 'index.php?option=com_projects&amp;view=plans', $vName == 'plans');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), 'index.php?option=com_projects&amp;view=activities', $vName == 'activities');
	}

    /**
     * Возвращает русское значение единицы измерения пункта прайса
     * @param string $name  Значение из таблицы
     * @return string   Нормальное название единицы измерения
     * @since 1.1.4
     */
    public static function getUnit(string $name): string
    {
        $name = mb_strtoupper($name);
        try
        {
            $text = JText::sprintf("COM_PROJECTS_HEAD_ITEM_UNIT_{$name}");
        }
        catch (Exception $exception)
        {
            $text = JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_UNKNOWN');
        }
        return $text;
    }
}
