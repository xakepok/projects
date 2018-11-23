<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerStand extends FormController {

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $contractID = $this->input->getInt('contractID', 0);
        if ($contractID != 0)
        {
            $session = JFactory::getSession();
            $session->set('contractID', $contractID);
        }
        return parent::add();
    }
}