<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewExhibitor extends HtmlView {
    protected $item, $form, $script, $id, $activities, $history, $coExhibitors, $persons;

    public function display($tmp = null) {
        $this->id = JFactory::getApplication()->input->getInt('id', 0);
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->activities = $this->get('Activities');
        $this->script = $this->get('Script');
        $this->history = $this->get('History');
        $this->coExhibitors = $this->get('CoExhibitors');
        $this->persons = $this->get('Persons');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = ($this->id != 0) ? ProjectsHelper::getExpTitle($this->item->title_ru_short, $this->item->title_ru_full, $this->item->title_en) : JText::sprintf('COM_PROJECTS_TITLE_NEW_EXHIBITOR');

        JToolbarHelper::title($title, '');
	    //JToolBarHelper::apply('exhibitor.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('exhibitor.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('exhibitor.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}