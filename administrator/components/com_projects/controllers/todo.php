<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsControllerTodo extends FormController {

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function add()
    {
        $contractID = $this->input->getInt('contractID', 0);
        $return_from_url = $this->input->getString('return', null);
        $session = JFactory::getSession();
        $createTodoFor = $session->get('createTodoFor', null);
        if ($contractID != 0 || $createTodoFor != null)
        {
            $cid = ($contractID != 0) ? $contractID : $createTodoFor;
            $session->set('contractID', $cid);
            if ($return_from_url == null) {
                $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=todos&contractID={$cid}");
                $this->input->set('return', $return);
            }
        }
        return parent::add();
    }

    public function cancel($key = null)
    {
        $layout = JFactory::getApplication()->input->getCmd('layout', 'default');
        if ($layout == 'notify') {
            $model = AdminModel::getInstance('Todo', 'ProjectsModel');
            $model->close();
        }
        return parent::cancel($key);
    }
}