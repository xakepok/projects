<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerItems extends AdminController
{
    public function getModel($name = 'Item', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
