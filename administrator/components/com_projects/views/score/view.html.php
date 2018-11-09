<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewScore extends HtmlView {
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
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = $this->item->number ?? JText::sprintf('COM_PROJECTS_TITLE_NEW_SCORE');

        JToolbarHelper::title($title, '');
	    JToolBarHelper::apply('score.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('score.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('score.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}