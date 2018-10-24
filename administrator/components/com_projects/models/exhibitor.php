<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelExhibitor extends AdminModel {
    public function getTable($name = 'Exponents', $prefix = 'TableProjects', $options = array())
    {
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.exhibitor', 'exhibitor', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.exhibitor.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.exhibitor.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
    	$nulls = array('title_ru_short', 'title_ru_full', 'title_en'); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.exhibitor.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/exhibitor.js';
    }


    public function save($data)
    {
        $res = parent::save($data);
        $db =& $this->getDbo();
        $id = (JFactory::getApplication()->input->getInt('id')) ?? $db->insertid();
        $this->saveBank($data, $id);
        return $res;
    }

    /*Сохраняем банковские реквизиты*/
    private function saveBank($data, int $id = 0)
    {
        $data['exbID'] = $id;
        unset($data['regID'], $data['curatorID'], $data['title_ru_full'], $data['title_ru_short'], $data['title_en'], $data['state'], $data['tags'], $data['id']);
        $db =& $this->getDbo();
        $table = $db->quoteName("#__prj_exp_bank");
        $arr = array(
            $db->quoteName('exbID') => $db->quote($id),
            $db->quoteName('inn') => $db->quote($data['inn']),
            $db->quoteName('kpp') => $db->quote($data['kpp']),
            $db->quoteName('rs') => $db->quote($data['rs']),
            $db->quoteName('ks') => $db->quote($data['ks']),
            $db->quoteName('bank') => $db->quote($db->escape($data['bank'])),
            $db->quoteName('bik') => $db->quote($data['bik'])
        );
        $columns = array();
        $values = array();
        $set = array();
        foreach ($arr as $item => $value)
        {
            array_push($columns, $item);
            array_push($values, $value);
            array_push($set, sprintf("%s = %s", $item, $value));
        }
        $columns = implode(', ', $columns);
        $values = implode(', ', $values);
        $set = implode(', ', $set);
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values}) ";
        unset($arr[$db->quoteName('exbID')]);
        $query .= "ON DUPLICATE KEY UPDATE {$set}";
        return $db->setQuery($query)->execute();
    }

}