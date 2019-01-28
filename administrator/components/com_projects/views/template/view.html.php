<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsViewTemplate extends HtmlView {
    protected $item, $form, $script, $id, $isAdmin;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->isAdmin = ProjectsHelper::canDo('core.general');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        JToolbarHelper::title($item->title ?? JText::sprintf('COM_PROJECTS_BLANK_TEMPLATE'), '');
        JToolBarHelper::apply('template.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('template.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('template.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}