<?php
use Joomla\CMS\MVC\Model\ItemModel;

defined('_JEXEC') or die;

class ProjectsModelProfile extends ItemModel
{
    public function __construct($config = array())
    {
        $this->contractID = ProjectsHelper::getUserContractID();
        parent::__construct($config);
    }

    public function getItem(): array
    {
        $contracts_table = $this->getTable('Contracts');
        if ($this->contractID == 0) return array();
        $contracts_table->load($this->contractID);
        $exhibitorID = $contracts_table->expID;
        $table = $this->getTable();
        $table->load($exhibitorID);
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

    private $contractID;
}
