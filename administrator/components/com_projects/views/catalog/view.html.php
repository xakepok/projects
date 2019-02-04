<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewCatalog extends HtmlView {
    protected $item, $form, $script, $id, $isAdmin, $fieldset;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->id = $this->get('Id');
        if ($this->id > 0) $this->fieldset = $this->get('Fieldset');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = $this->item->number ?? $this->item->title ?? JText::sprintf('COM_PROJECTS_TITLE_NEW_OBJECT_INTO_CATALOG');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('catalog.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('catalog.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('catalog.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}