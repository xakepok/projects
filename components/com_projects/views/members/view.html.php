<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewMembers extends HtmlView
{
    public function display($tpl = null)
    {
        $this->setTitle();
        return parent::display($tpl);
    }

    protected function setTitle()
    {
        $this->document->setTitle('Members');
    }
}
