<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewCatalog extends HtmlView {
    protected $item, $form, $script, $id, $isAdmin;

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
        $title = $this->item->number ?? JText::sprintf('COM_PROJECTS_TITLE_NEW_STAND_INTO_CATALOG');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('catalog.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('catalog.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('catalog.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}