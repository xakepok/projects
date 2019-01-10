<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelScore extends AdminModel {
    public function getTable($name = 'Scores', $prefix = 'TableProjects', $options = array())
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
        $dat = new DateTime($data['dat']);
        $data['dat'] = $dat->format("Y-m-d");
        $s = parent::save($data);
        $id = $data['id'] ?? $this->getTable()->getDbo()->insertid();
        $this->checkState($id);
        return $s;
    }

    /**
     * Проверяет сумму по счёту, обновляет состояние счёта и создаёт задание в планировщике
     * Необходимо вызывать метод ПОСЛЕ совершения транзакции с таблицей счетов или платежей
     * @param int $scoreID ID сделки
     * @return void
     * @since 1.3.0.9
     * @throws Exception
     */
    public function checkState(int $scoreID): void
    {
        $pm = ListModel::getInstance('Payments', 'ProjectsModel');
        $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
        $score = $this->getItem($scoreID);
        $payments = (float) round($pm->getScorePayments($scoreID), 2);
        $contract = $cm->getItem($score->contractID);
        $score->amount = (float) round($score->amount, 2);
        $item = parent::getItem($scoreID);
        if ($score->amount > $payments)
        {
            $data = array('id' => $item->id, 'state' => 0);
            $this->getTable()->bind($data);
            parent::save($data);
        }
        if ($payments >= $score->amount)
        {
            $item = $this->getItem($scoreID);
            $data = array('id' => $item->id, 'state' => 1);
            $this->getTable()->bind($data);
            parent::save($data);
            if ($payments > $score->amount)
            {
                $data = array();
                $data['contractID'] = $score->contractID;
                $data['managerID'] = $contract->managerID;
                $data['task'] = JText::sprintf('COM_PROJECT_TASK_CHECK_SCORE', $score->number, $payments-$score->amount, $contract->currency);
                $this->_createTodo($data);
            }
        }
    }

    /**
     * Создаёт задание в планировщике
     * @param array $data Массив с добавляемыми данными
     * @return bool
     * @throws Exception
     * @since 1.3.0.9
     */
    protected function _createTodo(array $data): bool
    {
        $arr = array();
        $arr['id'] = NULL;
        $arr['dat'] = date('Y-m-d', strtotime(' +1 day'));
        $arr['user_open'] = JFactory::getUser()->id;
        $data['result'] = NULL;
        $data['user_close'] = NULL;
        $data['dat_close'] = NULL;
        $arr['state'] = 0;
        $arr = array_merge($arr, $data);
        $model = AdminModel::getInstance('Todo', 'ProjectsModel');
        $table = $model->getTable();
        $table->bind($arr);
        return $model->save($arr);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.score', 'score', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.score.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.score.data', array());
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

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.score.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/score.js';
    }
}