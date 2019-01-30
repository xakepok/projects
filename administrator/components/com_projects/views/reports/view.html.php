<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewReports extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links, $isAdmin, $filterForm, $activeFilters, $type, $fields;

	public function display($tpl = null)
	{
        $this->type = $this->get('Type');
        $this->items = (!empty($this->type)) ? $this->get('Items') : array();
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->fields = $this->state->get('filter.fields', array());

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('reports');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
	    $title = (!empty($this->type)) ? ProjectsHelper::getReportType($this->type) : JText::sprintf('COM_PROJECTS_MENU_REPORTS_DESC');
		JToolBarHelper::title($title, '');
		if (!empty($this->type))
        {
            JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_projects&view=reports');
        }
        if (ProjectsHelper::canDo('core.admin'))
        {
            JToolBarHelper::preferences('com_projects');
        }
    }
}
