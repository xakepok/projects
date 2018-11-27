<?php
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Response\JsonResponse;
defined('_JEXEC') or die;

class ProjectsControllerPayments extends AdminController
{
    public function getModel($name = 'Payment', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
