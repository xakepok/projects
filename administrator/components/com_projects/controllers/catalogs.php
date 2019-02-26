<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerCatalogs extends AdminController
{
    public function getModel($name = 'Catalog', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function removeCatalog()
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
            $this->setMessage(JText::sprintf('COM_PROJECTS_TABLE_ERROR_DELETE_CATALOG'), 'error');
        }
        else {
            $this->setMessage(JText::sprintf('COM_PROJECTS_CATALOG_DELETED'));
        }
        $this->redirect();
        jexit();
    }

}
