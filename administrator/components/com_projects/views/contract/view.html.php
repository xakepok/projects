<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewContract extends HtmlView {
    protected $item, $form, $script, $id, $price, $todos;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->price = $this->get('Price');
        if ($this->item->id != null) $this->todos = $this->get('Todos');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = ($this->item->id != null) ? $this->get('Title') : JText::sprintf('COM_PROJECTS_TITLE_NEW_CONTRACT');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('contract.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('contract.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('contract.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
    }
}