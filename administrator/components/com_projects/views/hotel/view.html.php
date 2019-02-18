<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewHotel extends HtmlView {
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
        $title = $this->item->title_ru ?? $this->item->title_en ?? JText::sprintf('COM_PROJECTS_MENU_HOTEL');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('hotel.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('hotel.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('hotel.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}