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
     * Присваивает сделке номер договора
     * @since 1.2.2
     */
    public function getNumber()
    {
        $app = JFactory::getApplication();
        $number = ProjectsHelper::getContractNumber();
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $item = $model->getItem($id);
            if ($item->status != 1)
            {
                $app->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_CONTRACT_IS_NOT_DOGOVOR'), 'error');
                $app->redirect(JRoute::_('index.php?option=com_projects&view=contracts'));
                jexit();
            }
            if ($item->number != null)
            {
                $app->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_CONTRACT_ALREADY_HAVE_NUMBER', $item->number), 'error');
                $app->redirect(JRoute::_('index.php?option=com_projects&view=contracts'));
                jexit();
            }
            $data = array('number' => $number, 'id' => $item->id, 'status' => $item->status);
            $table = $model->getTable();
            $table->bind($data);
            $model->save($data);
        }
        $app->enqueueMessage(JText::sprintf('COM_PROJECTS_MESSAGE_CONTRACT_HAVE_NUM', $number), 'success');
        $app->redirect(JRoute::_('index.php?option=com_projects&view=contracts'));
        jexit();
    }

    /**
     * Расчёт и фиксация цены сделки
     * @return void
     * @since 1.2.9.2
     */
    public function calculate(): void
    {
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $model->calculate($id);
        }
        $msg = JText::sprintf('COM_PROJECTS_MESSAGE_CALCULATE_SUCCESS');
        $url = JRoute::_('index.php?option=com_projects&view=contracts');
        $this->setRedirect($url, $msg)->redirect();
        jexit();
    }

    /**
     * Сброс расчитанной цены сделки
     * @return void
     * @since 1.2.9.2
     */
    public function resetAmount(): void
    {
        $ids = $this->input->get('cid');
        $model = $this->getModel();
        foreach ($ids as $id)
        {
            $model->resetAmount($id);
        }
        $msg = JText::sprintf('COM_PROJECTS_MESSAGE_CALCULATE_RESET');
        $url = JRoute::link('administrator', 'index.php?option=com_projects&view=contracts');
        $this->setRedirect($url, $msg)->redirect();
        jexit();
    }
}
