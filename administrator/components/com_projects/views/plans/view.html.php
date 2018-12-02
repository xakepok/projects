<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewPlans extends HtmlView
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
		$this->helper->addSubmenu('plans');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_PLANS'), '');

        if (ProjectsHelper::canDo('core.create'))
        {
            JToolbarHelper::addNew('plan.add');
        }
        if (ProjectsHelper::canDo('core.edit'))
        {
            JToolbarHelper::editList('plan.edit');
        }
        if (ProjectsHelper::canDo('core.delete') && ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('', 'plans.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
