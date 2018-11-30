<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerPerson extends FormController {

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $exbID = $this->input->getInt('exbID', 0);
        if ($exbID != 0)
        {
            $session = JFactory::getSession();
            $session->set('exbID', $exbID);
        }
        return parent::add();
    }

    public function edit($key = null, $urlVar = null)
    {
        $exbID = $this->input->getInt('exbID', 0);
        if ($exbID != 0)
        {
            $session = JFactory::getSession();
            $session->set('exbID', $exbID);
        }
        return parent::edit($key, $urlVar);
    }
}