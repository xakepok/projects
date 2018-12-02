<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewSections extends HtmlView
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
		$this->helper->addSubmenu('sections');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_SECTIONS'), '');

        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::addNew('section.add');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::editList('section.edit');
        }
        if (ProjectsHelper::canDo('core.general'))
        {
            JToolbarHelper::deleteList('', 'sections.delete');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('sections.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('sections.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('sections.archive');
            JToolBarHelper::trash('sections.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
