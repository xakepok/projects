<?php
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Response\JsonResponse;
defined('_JEXEC') or die;

class ProjectsControllerTodos extends AdminController
{
    public function getModel($name = 'Todo', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function exportxls(): void
    {
        $model = ListModel::getInstance('Todos', 'ProjectsModel');
        $model->exportToExcel();
        jexit();
    }

    public function getTodosCountOnDate()
    {
        $dat = $this->input->getString('date', null);
        $uid = $this->input->getInt('uid', 0);
        if ($dat == null)
        {
            echo new JsonResponse(array("error" => JText::sprintf('COM_PROJECTS_ERROR_EMPTY_DATE')));
            jexit();
        }
        $dat = JFactory::getDbo()->escape($dat);
        $model = $this->getModel();
        $cnt = $model->getTodosCountOnDate($dat, $uid);
        echo new JsonResponse(array("cnt" => $cnt));
        jexit();
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

    public function removeTodo()
    {
        $id = $this->input->getInt('id', 0);
        if ($id <= 0) jexit();
        $model = $this->getModel();
        $model->delete($id);
        echo new JsonResponse(array('result' => 'ok'));
        jexit();
    }

    public function toweek()
    {
        $cid = $this->input->get('cid');
        $ids = array();
        foreach ($cid as $id)
        {
            $ids[] = $id;
        }
        $model = $this->getModel();
        $model->toWeek($ids, "1 week");
        $this->setMessage(JText::sprintf('COM_PROJECTS_MESSAGE_TODOS_WEEK_SUCCESS'));
        $this->setRedirect('index.php?option=com_projects&view=todos');
        $this->redirect();
        jexit();
    }

    public function read()
    {
        $cid = $this->input->get('cid');
        $ids = array();
        foreach ($cid as $id)
        {
            $ids[] = $id;
        }
        $model = $this->getModel();
        $model->read($ids);
        $this->setMessage(JText::sprintf('COM_PROJECTS_MESSAGE_NOTIFIES_WROTE'));
        $this->setRedirect("index.php?option=com_projects&view=todos&notify=1");
        $this->redirect();
        jexit();
    }
}
