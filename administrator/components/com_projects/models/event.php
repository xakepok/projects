<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelEvent extends AdminModel {
    public function getTable($name = 'Ual', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.event', 'event', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        return $form;
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.event.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
        $data['userID'] = JFactory::getUser()->id;
        $data['dat'] = date("Y-m-d H:i:s");
        $data['params'] = json_encode($data['params']);
        $data['old_data'] = json_encode($data['old_data']);
        return parent::save($data);
    }

    protected function prepareTable($table)
    {
    	$nulls = array('params'); //Поля, которые NULL

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

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/event.js';
    }
}