<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewScores extends HtmlView
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
		$this->helper->addSubmenu('scores');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(Text::_('COM_PROJECTS_MENU_SCORES'), '');

        if (Factory::getUser()->authorise('core.create', 'com_projects'))
        {
            JToolbarHelper::addNew('score.add');
        }
        if (Factory::getUser()->authorise('core.edit', 'com_projects'))
        {
            JToolbarHelper::editList('score.edit');
        }
        if ($this->state->get('filter.state') == -2 && Factory::getUser()->authorise('core.delete', 'com_projects'))
        {
            JToolbarHelper::deleteList('', 'scores.delete');
        }
        if (Factory::getUser()->authorise('core.edit.state', 'com_projects'))
        {
            JToolbarHelper::divider();
            JToolbarHelper::publish('scores.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('scores.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::archiveList('scores.archive');
            JToolBarHelper::trash('scores.trash');
        }
		if (Factory::getUser()->authorise('core.admin', 'com_projects'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
