<?php
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class ProjectsViewStatv2 extends HtmlView
{
	protected $helper;
	protected $sidebar = '';
	public $items, $pagination, $uid, $state, $links, $filterForm, $activeFilters, $return, $action, $itemID, $priceItem;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->itemID = $this->get('ItemID');
	    $this->priceItem = $this->get('PriceItem');
	    $this->return = ProjectsHelper::getReturnUrl();
	    $this->action = ProjectsHelper::getActionUrl();
	    $this->pagination = $this->get('Pagination');
	    $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        if (!ProjectsHelper::canDo('projects.access.stat.full')) {
            $this->filterForm->removeField('manager', 'filter');
        }

        // Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new ProjectsHelper();
		$this->helper->addSubmenu('statv2');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	private function toolbar()
	{
		JToolBarHelper::title(($this->itemID > 0) ? $this->priceItem : Text::_('COM_PROJECTS_MENU_STAT_DESC'), '');
		if ($this->itemID > 0) {
            JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_projects&view=statv2');
        }

		if (ProjectsHelper::canDo('core.admin'))
		{
			JToolBarHelper::preferences('com_projects');
		}
	}
}
