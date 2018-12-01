<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelTodo extends AdminModel {
    public function getTable($name = 'Todos', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $item->dat = $item->dat ?? date("Y-m-d");
        $item->managerID = $item->managerID ?? JFactory::getUser()->id;

        return $item;
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.todo', 'todo', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.todo.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.todo.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
        if ($data['id'] == 0) $data['userOpen'] = JFactory::getUser()->id;
        if ($data['id'] != 0 && $data['state'] == 1)
        {
            $data['userClose'] = JFactory::getUser()->id;
            $data['dat_close'] = date("Y-m-d H:i:s");
        }
        return parent::save($data);
    }

    /**
     * Закрывает задачу. Используется для асинхронного запроса из вьюшки со сделкой
     * @since 1.2.9.6
     * @return boolean
     * @throws
     */
    public function close()
    {
        $data['userClose'] = JFactory::getUser()->id;
        $data['dat_close'] = date("Y-m-d H:i:s");
        $data['result'] = JFactory::getDbo()->escape(JFactory::getApplication()->input->getString('result'));
        $data['id'] = JFactory::getDbo()->escape(JFactory::getApplication()->input->getInt('id', 0));
        $data['state'] = '1';
        if ($data['id'] == 0) return false;
        return parent::save($data);
    }

    protected function prepareTable($table)
    {
    	$nulls = array('userClose', 'result', 'dat_close'); //Поля, которые NULL
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
            return $user->authorise('core.edit.state', $this->option . '.todo.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/todo.js';
    }
}