<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerItem extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $projectID = $this->input->getInt('projectID', 0);
        if ($projectID != 0)
        {
            $session = JFactory::getSession();
            $session->set('projectID', $projectID);
        }
        return parent::add();
    }
}