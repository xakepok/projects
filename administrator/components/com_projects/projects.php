<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_projects'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Require the helper
JLoader::register('ProjectsHelper', dirname(__FILE__) . '/helpers/projects.php');
JLoader::register('ProjectsHtmlFilters', dirname(__FILE__) . '/helpers/html/filters.php');

// Execute the task
$controller = BaseController::getInstance('projects');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
