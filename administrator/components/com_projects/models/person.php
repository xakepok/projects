<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelPerson extends AdminModel {
    public function getTable($name = 'Persons', $prefix = 'TableProjects', $options = array())
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
    	$nulls = array('fio', 'post', 'phone_work', 'phone_mobile', 'email'); //Поля, которые NULL

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