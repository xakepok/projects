<?php
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Response\JsonResponse;

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
	    echo new JsonResponse($this->items);
	    jexit();
	}
}
