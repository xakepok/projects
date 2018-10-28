<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewExhibitor extends HtmlView {
    protected $item, $form, $script, $id, $activities;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->activities = $this->get('Activities');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = $this->item->title ?? JText::_('COM_PROJECTS_TITLE_NEW_EXHIBITOR');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('exhibitor.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('exhibitor.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('exhibitor.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . $this->script);
    }
}