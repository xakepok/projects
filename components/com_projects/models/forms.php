<?php
use Joomla\CMS\MVC\Model\ItemModel;

defined('_JEXEC') or die;

class ProjectsModelForms extends ItemModel
{
    public function __construct($config = array())
    {
        $this->exhibitorID = 1;
        $this->formType = JFactory::getApplication()->input->getString('type', 'forms');
        parent::__construct($config);
    }

    public function getItem(): array
    {

    }

    public function getFormType()
    {
        return $this->formType;
    }

    public function getTable($name = 'Profiles', $prefix = 'TableProjects', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    private $exhibitorID, $formType;
}
