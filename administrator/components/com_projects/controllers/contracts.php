<?php
use Joomla\CMS\MVC\Controller\AdminController;
use \Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Model\ListModel;
defined('_JEXEC') or die;

class ProjectsControllerContracts extends AdminController
{
    public function getModel($name = 'Contract', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function exportxls(): void
    {
        $model = ListModel::getInstance('Contracts', 'ProjectsModel');
        $model->exportToExcel();
        jexit();
    }

    /**
     * Удаляет стенд
     * @since 1.3.0.2
     */
    public function removeStand()
    {
        $id = $this->input->getInt('id', 0);
        if ($id == 0)
        {
            $result = array('result' => 0, 'message' => 'Empty ID');
            echo new JsonResponse($result);
            jexit();
        }
        $model = $this->getModel('Stand', 'ProjectsModel');
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

    /**
     * Присваивает сделке номер договора
     * @since 1.2.2
     */
    public function getNumber()
    {
        $app = JFactory::getApplication();
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $item = $model->getItem($id);
            $number = ProjectsHelper::getContractNumber($item->prjID);
            if ($item->status != 1)
            {
                $app->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_CONTRACT_IS_NOT_DOGOVOR'), 'error');
                $app->redirect('index.php?option=com_projects&view=contracts');
                jexit();
            }
            if ($item->number != null)
            {
                $app->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_CONTRACT_ALREADY_HAVE_NUMBER', $item->number), 'error');
                $app->redirect('index.php?option=com_projects&view=contracts');
                jexit();
            }
            $data = array('number' => $number, 'id' => $item->id, 'status' => $item->status, 'managerID' => $item->managerID);
            $table = $model->getTable();
            $table->bind($data);
            $model->save($data);
        }
        $app->enqueueMessage(JText::sprintf('COM_PROJECTS_MESSAGE_CONTRACT_HAVE_NUM', $number), 'success');
        $app->redirect('index.php?option=com_projects&view=contracts');
        jexit();
    }

    public function setcolumn1()
    {
        $app = JFactory::getApplication();
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $model->setColumn($id, 1);
        }
        $app->enqueueMessage(JText::sprintf('COM_PROJECT_TASK_COLUMN_EDITED'), 'success');
        $app->redirect('index.php?option=com_projects&view=contracts');
        jexit();
    }

    public function setcolumn2()
    {
        $app = JFactory::getApplication();
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $model->setColumn($id, 2);
        }
        $app->enqueueMessage(JText::sprintf('COM_PROJECT_TASK_COLUMN_EDITED'), 'success');
        $app->redirect('index.php?option=com_projects&view=contracts');
        jexit();
    }

    public function setcolumn3()
    {
        $app = JFactory::getApplication();
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $model->setColumn($id, 3);
        }
        $app->enqueueMessage(JText::sprintf('COM_PROJECT_TASK_COLUMN_EDITED'), 'success');
        $app->redirect('index.php?option=com_projects&view=contracts');
        jexit();
    }
}
