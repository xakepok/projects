<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewPerson extends HtmlView {
    protected $item, $form, $script, $id;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = JText::sprintf('COM_PROJECTS_BLANK_PERSON');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('person.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('person.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('person.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}