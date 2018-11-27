<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerPayment extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $scoreID = $this->input->getInt('scoreID', 0);
        if ($scoreID != 0)
        {
            $session = JFactory::getSession();
            $session->set('scoreID', $scoreID);
        }
        return parent::add();
    }
}