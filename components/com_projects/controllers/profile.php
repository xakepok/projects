<?php
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;

class ProjectsControllerProfile extends BaseController
{
    public function setUserContract()
    {
        $contractID = JFactory::getApplication()->input->getInt('contractID', 0);
        $return = JFactory::getApplication()->input->getString('return');
        $available = ProjectsHelper::getUserContracts(false);
        if (in_array($contractID, $available) && $contractID > 0) {
            ProjectsHelper::setUserContract($contractID);
        }
        if ($return !== null) {
            $return = $_SERVER['HTTP_REFERER'];
            $this->setRedirect($return);
            $this->redirect();
            jexit();
        }
    }
}
