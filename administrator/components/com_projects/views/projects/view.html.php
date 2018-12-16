<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewProjects extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links, $filterForm, $activeFilters;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
	    $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('projects');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_PROJECTS'), '');

        if (ProjectsHelper::canDo('core.create') && ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('project.add');
        }
        if (ProjectsHelper::canDo('core.edit') && ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('project.edit');
        }
        if (ProjectsHelper::canDo('core.delete') && ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('', 'projects.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
