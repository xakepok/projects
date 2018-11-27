<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewItem extends HtmlView {
    protected $item, $form, $script, $id, $price;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->price = $this->get('PriceName');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = $this->item->title_ru ?? $this->item->title_en ?? JText::sprintf('COM_PROJECTS_TITLE_NEW_ITEM');
        if ($this->item->id != null) $title .= " ({$this->price})";

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('item.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . $this->script);
    }
}