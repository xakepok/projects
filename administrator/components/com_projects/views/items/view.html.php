<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewItems extends HtmlView
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
		$this->helper->addSubmenu('items');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_ITEMS'), '');

        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('item.add');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('item.edit');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('', 'items.delete');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('items.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('items.archive');
            JToolBarHelper::trash('items.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
