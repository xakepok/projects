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

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    protected function loadFormData()
    {

    }

    protected function prepareTable($table)
    {
    	$nulls = array('indexcode', 'addr_legal_street', 'addr_legal_home', 'addr_fact_street', 'addr_fact_home', 'phone_1', 'phone_2', 'fax', 'email', 'site', 'director_name', 'director_post', 'contact_person', 'contact_data'); //Поля, которые NULL

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