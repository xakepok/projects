<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerSections extends AdminController
{
    public function getModel($name = 'Section', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function import()
    {
        $from = $this->input->getInt('from', 0);
        $to = $this->input->getInt('to', 0);
        if ($from == 0 || $to == 0)
        {
            $message = JText::sprintf('COM_PROJECTS_MESSAGE_IMPORT_ERROR_NOT_ID');
            $this->setRedirect('index.php?option=com_projects&view=sections', $message)->redirect();
            jexit();
        }
        $model = $this->getModel();
        $result = $model->import($from, $to);
        $message = JText::sprintf((!$result) ? 'COM_PROJECTS_MESSAGE_IMPORT_ERROR' : 'COM_PROJECTS_MESSAGE_IMPORT_SUCCESS');
        $url = JRoute::_("index.php?option=com_projects&view=sections&filter_price={$to}");
        $this->setRedirect($url, $message)->redirect();
        jexit();
    }
}
