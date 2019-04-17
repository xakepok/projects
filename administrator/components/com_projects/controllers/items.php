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

    public function standard()
    {
        $model = $this->getModel();
        $cid = $this->input->get('cid');
        $ids = array();
        foreach ($cid as $id)
        {
            $ids[] = $id;
        }
        $model->setStandardColumns($ids);
        $this->setMessage(JText::sprintf('COM_PROJECTS_MESSAGE_SET_STANDARD_VALUES'), 'success');
        $this->setRedirect('index.php?option=com_projects&view=items');
        $this->redirect();
        jexit();
    }
}
