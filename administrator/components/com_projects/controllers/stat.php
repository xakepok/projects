<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerStat extends AdminController
{
    public function getModel($name = 'Stat', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function exportxls(): void
    {
        $model = $this->getModel();
        $model->exportToExcel();
        jexit();
    }
}
