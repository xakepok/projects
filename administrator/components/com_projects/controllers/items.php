<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerItems extends AdminController
{
    public function getModel($name = 'Item', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function removeItem()
    {
        $model = $this->getModel();
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $return = JFactory::getApplication()->input->getString('return', 0);
        $url = base64_decode($return);
        $this->setRedirect($url);
        if ($id == 0) {
            $this->redirect();
            jexit();
        }
        if (!$model->delete($id)) {
            $this->setMessage(JText::sprintf('COM_PROJECTS_TABLE_ERROR_DELETE_ITEM'), 'error');
        }
        else {
            $this->setMessage(JText::sprintf('COM_PROJECTS_ITEM_DELETED'));
        }
        $this->redirect();
        jexit();
    }

    public function standard_columns()
    {
        $model = $this->getModel();
        $cid = $this->input->get('cid');
        $columns = array(1 => 1.0, 2 => 1.5, 3 => 2);
        $ids = array();
        foreach ($cid as $id)
        {
            $ids[] = $id;
        }
        $model->setColumnsValues($ids, $columns);
        $this->setMessage(JText::sprintf('COM_PROJECTS_MESSAGE_SET_STANDARD_VALUES'));
        $this->setRedirect('index.php?option=com_projects&view=items');
        $this->redirect();
        jexit();
    }

    public function reset_columns()
    {
        $model = $this->getModel();
        $cid = $this->input->get('cid');
        $columns = array(1 => 1, 2 => 1, 3 => 1);
        $ids = array();
        foreach ($cid as $id)
        {
            $ids[] = $id;
        }
        $model->setColumnsValues($ids, $columns);
        $this->setMessage(JText::sprintf('COM_PROJECTS_MESSAGE_RESET_VALUES'));
        $this->setRedirect('index.php?option=com_projects&view=items');
        $this->redirect();
        jexit();
    }
}
