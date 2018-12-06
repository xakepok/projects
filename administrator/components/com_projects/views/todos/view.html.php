<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewTodos extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links, $isAdmin;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
	    $this->state = $this->get('State');
	    $this->isAdmin = ProjectsHelper::canDo('core.general');

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('todos');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_TODOS'), '');

        if (ProjectsHelper::canDo('core.create'))
        {
            JToolbarHelper::addNew('todo.add');
        }
        if (ProjectsHelper::canDo('core.edit'))
        {
            JToolbarHelper::editList('todo.edit');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('todos.publish', 'COM_PROJECTS_ACTION_TASK_DOES', true);
            JToolbarHelper::unpublish('todos.unpublish', 'COM_PROJECTS_ACTION_TASK_DOSE_DOES', true);
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
