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

    public function changeColumn()
    {
        $model = $this->getModel();
        $id = $this->input->getInt('id', 0);
        $column = $this->input->getInt('column', 1);
        $item = $model->getItem($id);
        if ($id != null) {
            $data = array('id' => $id, 'column' => $column);
            if (!$model->save($data)) {
                $msg = JText::sprintf('COM_PROJECTS_ERROR_UNKNOWN_NOT_TRANSFER');
                $type = 'error';
            }
            else {
                $msg = JText::sprintf('COM_PROJECT_TASK_COLUMN_EDITED');
                $type = 'success';
            }
        }
        else {
            $msg = JText::sprintf('COM_PROJECTS_ERROR_UNKNOWN_ID');
            $type = 'error';
        }
        $return = base64_decode($this->input->getString('return'));
        $this->setRedirect($return, $msg, $type);
        $this->redirect();
        jexit();
    }

    public function getModel($name = 'Ctritem', $prefix = 'ProjectsModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}