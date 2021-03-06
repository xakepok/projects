<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerContract extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $exhibitorID = $this->input->getInt('exhibitorID', 0);
        $projectID = $this->input->getInt('projectID', 0);
        if ($exhibitorID != 0)
        {
            $session = JFactory::getSession();
            $session->set('exbID', $exhibitorID);
        }
        if ($projectID != 0)
        {
            $session = JFactory::getSession();
            $session->set('projectID', $projectID);
        }
        return parent::add();
    }
}