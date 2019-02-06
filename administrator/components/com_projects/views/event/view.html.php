<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewEvent extends HtmlView {
    protected $item, $script, $id;

    public function display($tmp = null) {
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        JToolbarHelper::title(JText::sprintf('COM_PROJECTS_BLANK_EVENT'));
        JToolbarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}