<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewProfile extends HtmlView
{
    protected $item;
    public function display($tpl = null)
    {
        $this->setTitle();
        $this->item = $this->get('Item');
        return parent::display($tpl);
    }

    protected function setTitle()
    {
        $this->document->setTitle('Profile');
    }
}
