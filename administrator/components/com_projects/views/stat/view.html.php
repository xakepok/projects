<?php
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewStat extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links, $isAdmin, $filterForm, $activeFilters, $itemID;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
	    $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->itemID = $this->get('ItemID');

        if (JFactory::getApplication()->input->get('itemID', 0) != 0) {
            $this->filterForm->removeField('item', 'filter');
        }

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('stat');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
	    $title = ($this->itemID != 0) ? $this->get('ExhibitorTitle') : Text::_('COM_PROJECTS_MENU_STAT_DESC');
		JToolBarHelper::title($title, '');
        if ($this->itemID != 0)
        {
            JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_projects&view=stat');
        }
        if (ProjectsHelper::canDo('core.admin'))
        {
            JToolBarHelper::preferences('com_projects');
        }
    }
}
