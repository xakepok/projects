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
        $url = JRoute::link('administrator', 'index.php?option=com_projects&view=contracts');
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
