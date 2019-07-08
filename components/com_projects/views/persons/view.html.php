<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewPersons extends HtmlView
{
    protected $items, $tmpl;
    public function display($tpl = null)
    {
        $this->setTitle();
        $this->items = $this->get('Items');
        $this->tmpl = $this->get('Tmpl');
        return parent::display($tpl);
    }

    protected function setTitle()
    {
        $this->document->setTitle('Persons');
    }
}
