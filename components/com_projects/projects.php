<?php
/**
 * @package    projects
 *
 * @author     asharikov <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/projects.php';

$controller = BaseController::getInstance('projects');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
