<?php
use Joomla\CMS\MVC\Model\ItemModel;

defined('_JEXEC') or die;

class ProjectsModelProfile extends ItemModel
{
    public function __construct($config = array())
    {
        $this->exhibitorID = 1;
        parent::__construct($config);
    }

    public function getItem(): array
    {
        $table = $this->getTable();
        $table->load($this->exhibitorID);
        $result = array();
        $result['title'] = $table->title_ru_full;
        $result['inn'] = $table->inn;
        $result['kpp'] = $table->kpp;
        $result['rs'] = $table->rs;
        $result['ks'] = $table->ks;
        $result['bank'] = $table->bank;
        $result['bik'] = $table->bik;

        return array_map('trim', $result);
    }

    public function getTable($name = 'Profiles', $prefix = 'TableProjects', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    private $exhibitorID;
}
