<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewCattitles extends HtmlView
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
		$this->helper->addSubmenu('cattitles');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(JText::sprintf('COM_PROJECTS_MENU_CATTITLES'), '');

        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('cattitle.add');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('cattitle.edit');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList(JText::sprintf('COM_PROJECT_QUEST_REMOVE_CATTITLES'), 'cattitles.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
