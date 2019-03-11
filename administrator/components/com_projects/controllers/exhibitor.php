<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerExhibitor extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function getModel($name = 'Exhibitor', $prefix = 'ProjectsModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function edit($key = null, $urlVar = null)
    {
        $view = $this->getView('exhibitor', 'html');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        if ($id > 0) {
            $model = $this->getModel();
            $layout = $model->getLayout();

            $this->input->set('layout', $layout);
            $view->setLayout($layout);
        }
        return parent::edit($key, $urlVar);
    }
}