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
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_PLANS'), 'index.php?option=com_projects&amp;view=plans', $vName == 'plans');
        JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), 'index.php?option=com_projects&amp;view=activities', $vName == 'activities');
		JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CFIELDS'), 'index.php?option=com_fields&amp;context=com_projects.price', $vName == 'fields');
		JHtmlSidebar::addEntry(Text::_('COM_PROJECTS_MENU_CFIELDS_GROUPS'), 'index.php?option=com_fields&amp;view=groups&amp;context=com_projects.price', $vName == 'groups');
	}
}
