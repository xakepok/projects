<?php
use Joomla\CMS\MVC\Controller\AdminController;
use \Joomla\CMS\Response\JsonResponse;
defined('_JEXEC') or die;

class ProjectsControllerContracts extends AdminController
{
    public function getModel($name = 'Contract', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    /**
     * Возвращает свободный номер договора в формате JSON
     * @since 1.2.2
     */
    public function getNumber()
    {
        $number = ProjectsHelper::getContractNumber();
        echo new JsonResponse(array('number' => $number));
        jexit();
    }
}
