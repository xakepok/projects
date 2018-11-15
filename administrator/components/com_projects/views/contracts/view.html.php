<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewContracts extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
	    $this->state = $this->get('State');

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

        if (Factory::getUser()->authorise('core.create', 'com_projects'))
        {
            JToolbarHelper::addNew('contract.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_projects'))
        {
            JToolbarHelper::editList('contract.edit');
        }
        JToolbarHelper::custom('contracts.calculate', '', '', 'COM_PROJECTS_ACTION_CALCULATE_CONTRACT');
        JToolbarHelper::divider();
        if (Factory::getUser()->authorise('projects.contract.allow', 'com_projects'))
        {
            JToolbarHelper::publish('contracts.publish', 'COM_PROJECTS_ACTION_ALLOW_CONTRACT', true);
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolBarHelper::archiveList('contracts.archive', 'COM_PROJECTS_ACTION_CLOSE_CONTRACT');
            JToolbarHelper::custom('contracts.resetAmount', '', '', 'COM_PROJECTS_ACTION_RESET_CONTRACT');
            //JToolBarHelper::trash('contracts.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
