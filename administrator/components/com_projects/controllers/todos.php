<?php
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Response\JsonResponse;
defined('_JEXEC') or die;

class ProjectsControllerTodos extends AdminController
{
    public function getModel($name = 'Todo', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function close()
    {
        $model = $this->getModel();
        $model->close();
        $user = JFactory::getUser()->name;
        $dat = date("d.m.Y");
        echo new JsonResponse(array("user" => $user, "dat" => $dat));
        jexit();
    }
}
