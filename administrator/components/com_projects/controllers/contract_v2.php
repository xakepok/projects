<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\FormController;

class ProjectsControllerContract_v2 extends FormController {
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }

    public function batch($model)
    {
        return parent::batch($model);
    }
}