<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class ProjectsControllerContracts_v2 extends AdminController
{
    public function getModel($name = 'Contracts_v2', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function export(): void
    {
        $model = $this->getModel();
        $items = $model->getItems();
        JLoader::discover('PHPExcel', JPATH_LIBRARIES);
        JLoader::register('PHPExcel', JPATH_LIBRARIES . '/PHPExcel.php');
        $xls = $model->export($items['items']);
        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: public");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Contracts.xls");
        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        if ($objWriter->save('php://output')) {
            header('Set-Cookie: fileLoading=true');
            $this->setRedirect("index.php?option=com_projects&view=contracts_v2");
            $this->redirect();
        }
        jexit();
    }
}
