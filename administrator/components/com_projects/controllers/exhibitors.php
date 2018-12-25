<?php
use Joomla\CMS\MVC\Controller\AdminController;
use \Joomla\CMS\Response\JsonResponse;
defined('_JEXEC') or die;

class ProjectsControllerExhibitors extends AdminController
{
    public function getModel($name = 'Exhibitor', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    /**
     * Удаляет контактное лицо
     * @since 1.3.0.9
     */
    public function removePerson()
    {
        $id = $this->input->getInt('id', 0);
        if ($id == 0)
        {
            $result = array('result' => 0, 'message' => 'Empty ID');
            echo new JsonResponse($result);
            jexit();
        }
        $model = $this->getModel('Person', 'ProjectsModel');
        $del = $model->delete($id);
        if (!$del)
        {
            $result = array('result' => 0, 'message' => $model->getErrors());
            echo new JsonResponse($result);
            jexit();
        }
        $result = array('result' => 1);
        echo new JsonResponse($result);
        jexit();
    }
}
