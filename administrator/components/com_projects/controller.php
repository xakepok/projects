<?php
/**
 * @package    projects
 *
 * @author     asharikov <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

/**
 * Projects Controller.
 *
 * @package  projects
 * @since    1.0
 */
class ProjectsController extends BaseController
{
    public function display($cachable = false, $urlparams = array())
    {
        $view = $this->input->getString('view');
        if ($view == 'todos')
        {
            $contractID = $this->input->getInt('contractID', 0);
            $session = JFactory::getSession();
            if ($contractID != 0)
            {
                $session->set('createTodoFor', $contractID);
            }
            else
            {
                $session->clear('createTodoFor');
            }
        }
        if ($view == 'todos') {
            $view = $this->getView('todos', 'html');
            $format = $this->input->getString('format', 'html');
            $layout = ($format != 'html') ? 'print' : 'default';
            $this->input->set('layout', $layout);
            $view->setLayout($layout);
        }
        return parent::display($cachable, $urlparams);
    }
}
