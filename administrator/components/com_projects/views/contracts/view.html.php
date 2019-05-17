<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewContracts extends HtmlView
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
        $this->filterForm->removeField('activity', 'filter');

        // Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('contracts');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_CONTRACTS'), '');

        if (ProjectsHelper::canDo('projects.access.contracts.standart'))
        {
            JToolbarHelper::addNew('contract.add');
        }
        if (ProjectsHelper::canDo('projects.access.contracts.standart'))
        {
            JToolbarHelper::editList('contract.edit');
        }
        /*if (ProjectsHelper::canDo('projects.access.contracts.standart'))
        {
            JToolbarHelper::deleteList('COM_PROJECT_QUEST_REMOVE_CONTRACT', 'contracts.delete');
        }*/
        if (ProjectsHelper::canDo('projects.access.contracts.columns')) {
            JToolbarHelper::custom('contracts.setcolumn1', '', '', 'COM_PROJECTS_ACTION_CONTRACT_SET_COLUMN_1');
            JToolbarHelper::custom('contracts.setcolumn2', '', '', 'COM_PROJECTS_ACTION_CONTRACT_SET_COLUMN_2');
            JToolbarHelper::custom('contracts.setcolumn3', '', '', 'COM_PROJECTS_ACTION_CONTRACT_SET_COLUMN_3');
        }
        JToolbarHelper::divider();
        if (ProjectsHelper::canDo('projects.access.contracts.standart'))
        {
            JToolbarHelper::custom('contracts.getNumber', '', '', 'COM_PROJECTS_ACTION_CONTRACT_SET_NUMBER');
        }
        if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
