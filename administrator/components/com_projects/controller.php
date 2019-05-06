<?php
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

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
            $v = $this->getView('todos', 'html');
            $format = $this->input->getString('format', 'html');
            $layout = ($format != 'html') ? 'print' : 'default';
            $this->input->set('layout', $layout);
            $v->setLayout($layout);
        }
        if ($view == 'catalogs') {
            $activeProject = ProjectsHelper::getActiveProject('');
            if ($activeProject != '') {
                $tip = ProjectsHelper::getProjectType($activeProject);
                $layout = ProjectsHelper::getProjectTypeName($tip);
                $this->input->set('layout', $layout);
                $v = $this->getView('catalogs', 'html');
                $v->setLayout($layout);
            }
        }
        return parent::display($cachable, $urlparams);
    }
}
