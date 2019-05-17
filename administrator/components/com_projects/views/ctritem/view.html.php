<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class ProjectsViewCtritem extends HtmlView {
    protected $item, $script, $id;

    public function display($tmp = null) {
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tmp);
    }

    protected function addToolbar() {
        JToolbarHelper::title($this->item->title);
        JToolbarHelper::save('ctritem.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('ctritem.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}