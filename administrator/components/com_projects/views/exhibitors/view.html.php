<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewExhibitors extends HtmlView
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
		$this->helper->addSubmenu('exhibitors');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_EXHIBITORS'), '');

        if ((ProjectsHelper::canDo('core.create') && !ProjectsHelper::canDo('core.accountant')) || ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('exhibitor.add');
        }
        if ((ProjectsHelper::canDo('core.edit') && !ProjectsHelper::canDo('core.accountant')) || ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('exhibitor.edit');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('', 'exhibitors.delete');
        }
		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
