<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewActivities extends HtmlView
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
		$this->helper->addSubmenu('activities');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_ACTIVITIES'), '');

        if (Factory::getUser()->authorise('core.create', 'com_projects'))
        {
            JToolbarHelper::addNew('activity.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_projects'))
        {
            JToolbarHelper::editList('activity.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_projects'))
        {
            JToolbarHelper::deleteList('', 'activities.delete');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('activities.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('activities.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('activities.archive');
            JToolBarHelper::trash('activities.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
