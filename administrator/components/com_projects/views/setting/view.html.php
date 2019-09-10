<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewSetting extends HtmlView {
    protected $item, $script, $id, $tab;

    public function display($tmp = null) {
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->script = $this->get('Script');
        $this->tab = $this->get('Tab');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tmp);
    }

    protected function addToolbar() {
        JToolbarHelper::title(JText::sprintf('COM_PROJECTS_MENU_SETTING'));
        JToolbarHelper::apply('setting.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('setting.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('setting.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}