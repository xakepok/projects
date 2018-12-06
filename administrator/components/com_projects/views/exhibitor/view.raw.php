<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Response\JsonResponse;

class ProjectsViewExhibitor extends HtmlView {
    protected $item, $id, $persons;

    public function display($tmp = null) {
        $this->id = JFactory::getApplication()->input->getInt('id', 0);
        $this->item = $this->get('Item');
        $this->persons = $this->get('Persons');
        echo new JsonResponse(array('info' => $this->item, 'persons' => $this->persons));
        jexit();
    }
}