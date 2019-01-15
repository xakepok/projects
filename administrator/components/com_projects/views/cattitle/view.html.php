<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewCattitle extends HtmlView {
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
        $title = $this->item->title ?? JText::sprintf('COM_PROJECTS_ACTION_ADD_CAT');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('cattitle.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('cattitle.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('cattitle.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}