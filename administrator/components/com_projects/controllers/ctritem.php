<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerCtritem extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $input = $this->input;
        JFactory::getApplication()->setUserState("{$this->option}.contractID", $input->getInt('contractID', 0));
        JFactory::getApplication()->setUserState("{$this->option}.itemID", $input->getInt('itemID', 0));
        JFactory::getApplication()->setUserState("{$this->option}.columnID", $input->getInt('columnID', 0));
        return parent::add();
    }

    public function getModel($name = 'Ctritem', $prefix = 'ProjectsModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}