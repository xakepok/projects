<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsViewTodo extends HtmlView {
    protected $item, $form, $script, $id, $isAdmin;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');
        $this->isAdmin = ProjectsHelper::canDo('projects.exec.edit');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolbar() {
        //JFactory::getApplication()->input->set('hidemainmenu', true);
        $layout = JFactory::getApplication()->input->getCmd('layout', 'default');
        $title = $this->setTitle();

        JToolbarHelper::title($title, '');
        if ($layout == 'edit') JToolBarHelper::apply('todo.apply', 'JTOOLBAR_APPLY');
        if ($layout == 'edit') JToolbarHelper::save('todo.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('todo.cancel', ($layout != 'edit') ? 'COM_PROJECTS_ACTION_CLOSE_AND_READ' : 'JTOOLBAR_CLOSE');
    }

    protected function setTitle(): string
    {
        if ($this->item->id == null)
        {
            $session = JFactory::getSession();
            if ($session->get('contractID') != null)
            {
                $contractID = $session->get('contractID');
                $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
                $contract = $cm->getItem($contractID);
                if ($contract->status != '1') $title = JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO_FOR_CONTRACT', $contractID);
                if ($contract->status == '1') {
                    if ($contract->number != null) $title = JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO_FOR_CONTRACT_DG', $contract->number);
                    if ($contract->number == null) $title = JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO_FOR_CONTRACT_WITHOUT_NUMBER');
                }
            }
            else
            {
                $title = JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO');
            }
        }
        else
        {
            $title = $this->item->task;
        }
        return $title;
    }

    protected function setDocument() {
        JHtml::_('bootstrap.framework');
    }
}