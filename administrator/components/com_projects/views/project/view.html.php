<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewProject extends HtmlView {
    protected $item, $form, $script, $id, $setManager, $setGroup;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->setManager = ProjectsHelper::canDo('projects.manager.edit');
        $this->setGroup = ProjectsHelper::canDo('projects.group.edit');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $title = $this->item->title ?? JText::sprintf('COM_PROJECTS_TITLE_NEW_PROJECT');

        JToolbarHelper::title($title, '');
	    //JToolBarHelper::apply('project.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('project.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
    }
}