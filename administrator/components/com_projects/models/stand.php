<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelStand extends AdminModel {
    public function getTable($name = 'Stands', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function delete(&$pks)
    {
        return parent::delete($pks);
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.stand', 'stand', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.stand.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
        if ($data['status'] == '2')
        {
            $this->_createTodo($data);
        }
        return parent::save($data);
    }

    protected function prepareTable($table)
    {
        $nulls = array('number', 'freeze', 'comment', 'scheme'); //Поля, которые NULL
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
            return $user->authorise('core.edit.state', $this->option . '.stand.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/stand.js';
    }


    /**
     * Создаёт задание в планировщике для утверждения отрисовки стенда
     * @param array $data Массив с добавляемыми данными
     * @return bool
     * @throws Exception
     * @since 1.3.0.9
     */
    protected function _createTodo(array $data): bool
    {
        $cdata = $this->__getContractData($data['contractID']);
        $arr = array();
        $arr['id'] = NULL;
        $arr['dat'] = date('Y-m-d', strtotime(' +1 day'));
        $arr['contractID'] = $data['contractID'];
        $arr['managerID'] = $cdata->managerID;
        $arr['task'] = JText::sprintf('COM_PROJECT_TASK_ACCEPT_SCHEME', $data['number'], $cdata->number);
        $arr['user_open'] = JFactory::getUser()->id;
        $arr['state'] = 0;
        $model = AdminModel::getInstance('Todo', 'ProjectsModel');
        $table = $model->getTable();
        $table->bind($arr);
        return $model->save($arr);
    }

    /**
     * Возвращает данные из договора для занесения в планировщик
     * @param int $contractID ID договора
     * @return object
     * @since 1.3.0.9
     */
    private function __getContractData(int $contractID): object
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`managerID`, `number`")
            ->from("`#__prj_contracts`")
            ->where("`id` = {$contractID}");
        return $db->setQuery($query, 0, 1)->loadObject();
    }

}