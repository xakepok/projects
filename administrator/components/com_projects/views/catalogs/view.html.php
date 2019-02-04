<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewCatalogs extends HtmlView
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
		$this->helper->addSubmenu('catalogs');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(JText::sprintf('COM_PROJECTS_BLANK_CATTITLE'), '');

        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('catalog.add');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('catalog.edit');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList(JText::sprintf('COM_PROJECT_QUEST_REMOVE_CATALOG'), 'catalogs.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
