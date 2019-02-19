<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelExparent extends AdminModel {
    public function getTable($name = 'Exparents', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function delete(&$pks)
    {
        $row = $this->getItem($pks);
        if ($row->id == null) return true;
        return parent::delete($pks);
    }

    public function getForm($data = array(), $loadData = true)
    {

    }

    protected function loadFormData()
    {

    }

    public function save($data)
    {
        return parent::save($data);
    }

    protected function prepareTable($table)
    {
    	$nulls = array(); //Поля, которые NULL

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