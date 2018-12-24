<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewPayments extends HtmlView
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
		$this->helper->addSubmenu('payments');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_PAYMENTS'), '');

        if (ProjectsHelper::canDo('core.accountant') || ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('payment.add');
        }
        if (ProjectsHelper::canDo('core.accountant') || ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('payment.edit');
        }
        if (ProjectsHelper::canDo('core.accountant') || ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('COM_PROJECT_QUEST_REMOVE_PAYMENTS', 'payments.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
