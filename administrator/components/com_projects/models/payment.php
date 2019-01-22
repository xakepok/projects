<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelPayment extends AdminModel {
    public function getTable($name = 'Payments', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        if (empty($item->dat)) $item->dat = date("Y-m-d");
        return $item;
    }

    public function save($data)
    {
        $data['created_by'] = JFactory::getUser()->id;
        $dat = new DateTime($data['dat']);
        $data['dat'] = $dat->format("Y-m-d");
        $sm = AdminModel::getInstance('Score', 'ProjectsModel');
        $result = parent::save($data);
        if ($data['id'] == null)
        {
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $arr = array();
            $score = $sm->getItem($data['scoreID']);
            $contract = $cm->getItem($score->contractID);
            $arr['contractID'] = $contract->id;
            $arr['managerID'] = $contract->managerID;
            $arr['task'] = JText::sprintf('COM_PROJECT_TASK_NEW_PAYMENT', $contract->number, ProjectsHelper::getCurrency((float) $data['amount'], $contract->currency));
            $this->notifyManager($arr);
        }
        $sm->checkState($data['scoreID']);
        return $result;
    }

    /**
     * Отправляет уведомление менеджеру сделки о проведении платежа по ней
     * @param array $data Массив с данными о уведомлении
     * @since 1.0.9.5
     */
    public function notifyManager(array $data): void
    {
        $todo = AdminModel::getInstance('Todo', 'ProjectsModel');
        $data['is_notify'] = 1;
        $data['state'] = '0';
        $arr['dat'] = date('Y-m-d');
        $todo->save($data);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.payment', 'payment', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.payment.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
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

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/payment.js';
    }
}