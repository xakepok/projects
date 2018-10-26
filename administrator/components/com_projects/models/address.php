<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelAddress extends AdminModel {
    public function getTable($name = 'Addresses', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {

    }

    protected function loadFormData()
    {

    }

    protected function prepareTable($table)
    {
    	$nulls = array('addr_legal_ru', 'addr_legal_en', 'addr_fact', 'phone_1', 'phone_2', 'fax', 'email', 'site', 'director_name', 'director_post', 'contact_person', 'contact_data'); //Поля, которые NULL

	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    public function publish(&$pks, $value = 1)
    {
        return parent::publish($pks, $value);
    }
}