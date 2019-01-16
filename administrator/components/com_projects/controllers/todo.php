<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerTodo extends FormController {

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $contractID = $this->input->getInt('contractID', 0);
        $session = JFactory::getSession();
        $createTodoFor = $session->get('createTodoFor', null);
        if ($contractID != 0 || $createTodoFor != null)
        {
            $cid = $createTodoFor ?? $contractID;
            $session->set('contractID', $cid);
        }
        return parent::add();
    }
}