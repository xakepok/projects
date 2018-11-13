<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelExhibitor extends AdminModel
{
    public function getTable($name = 'Exponents', $prefix = 'TableProjects', $options = array())
    {
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        $user_id =& JFactory::getUser()->id;
        $table = $this->getTable();
        $id = JFactory::getApplication()->input->get('id', 0);
        if ($id != 0)
        {
            $table->load($id);
            if ($table->isCheckedOut($user_id)) {
                JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_projects&view=exhibitors'), JText::sprintf('COM_PROJECTS_TABLE_ERROR_RECORD_BLOCKED'));
                jexit();
            } else {
                $table->checkOut($user_id);
            }
        }
        $item = parent::getItem($pk);
        $where = array('exbID' => $item->id);
        $bank = AdminModel::getInstance('Bank', 'ProjectsModel')->getItem($where);
        $address = AdminModel::getInstance('Address', 'ProjectsModel')->getItem($where);
        unset($item->_errors, $bank->exbID, $bank->id, $bank->_errors, $address->exbID, $address->id, $address->_errors);
        return (object)array_merge((array)$item, (array)$bank, (array)$address);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.exhibitor', 'exhibitor', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form)) {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.exhibitor.' . (int)$id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.exhibitor.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
        $nulls = array('title_ru_short', 'title_ru_full', 'title_en'); //Поля, которые NULL
        foreach ($nulls as $field) {
            if (!strlen($table->$field)) $table->$field = NULL;
        }
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', $this->option . '.exhibitor.' . (int)$record->id);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/exhibitor.js';
    }

    public function save($data)
    {
        $s1 = parent::save($data);

        $data = $this->addId($data);

        $s2 = $this->saveData('Bank', $data);
        $s3 = $this->saveData('Address', $data);
        $s4 = $this->saveActivities();
        return $s1 && $s2 && $s3 && $s4;
    }

    /**
     * Возвращает историю участия экспонента в проектах
     * @return array
     * @since 1.2.6
     * @throws
     */
    public function getHistory(): array
    {
        $expID = JFactory::getApplication()->input->getInt('id', 0);
        if ($expID == 0) return array();
        $model = AdminModel::getInstance('History', 'ProjectsModel');
        $history = $model->getExpHistory($expID);
        return $history;
    }

    /**
     * Получает массив видов деятельности для текущего экспонента.
     * @return  array   Массив
     * @since   1.1.3
     * @throws
     */
    public function getActivities(): array
    {
        $exbID = $this->getId();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__prj_exp_act'))
            ->where($db->quoteName('exbID') . " = " . $db->quote($exbID));
        $activities = $db->setQuery($query)->loadAssocList();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from("`#__prj_activities`")
            ->order($db->quoteName('title'));
        $list = $db->setQuery($query)->loadAssocList();

        $result = array();
        foreach ($list as $item) {
            $arr = array();
            $arr['id'] = $item['id'];
            $arr['title'] = $item['title'];
            $arr['checked'] = false;
            if ($exbID > 0) {
                foreach ($activities as $activity) {
                    if ($activity['exbID'] == $exbID && $activity['actID'] == $item['id']) {
                        $arr['checked'] = true;
                        break;
                    }
                }
            }
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Сохраняет запись в дочернюю таблицу видов деятельности.
     * @return  boolean True on success, False on error.
     * @since   1.1.3
     * @throws
     */
    private function saveActivities(): bool
    {
        $exbID = $this->getId();
        $post = $_POST['jform']['act'];
        if (empty($post)) return true;
        $model = AdminModel::getInstance('Act', 'ProjectsModel');
        $table = $model->getTable();
        foreach ($post as $act => $value) {
            $pks = array('exbID' => $exbID, 'actID' => $act);
            $row = $model->getItem($pks);
            if ($row->id != null)
            {
                if ($value == '')
                {
                    $model->delete($row->id);
                }
            }
            else {
                if ($value == '1') {
                    $arr['exbID'] = $exbID;
                    $arr['actID'] = $act;
                    $arr['id'] = null;
                    $table->bind($arr);
                    $model->save($arr);
                }
            }
        }
        return true;
    }

    /**
     * Сохраняем запись в дочернюю таблицу (кроме видов деятельности).
     * @param   string $modelName Краткое название модели.
     * @param   array $data Массив с добавляемыми данными.
     * @return  boolean True on success, False on error.
     * @since   1.1.2
     * @throws
     */
    private function saveData(string $modelName, array $data): bool
    {
        $model = AdminModel::getInstance($modelName, 'ProjectsModel');
        $table = $model->getTable()->bind($data);
        $model->prepareTable($table);
        return $model->save($data);
    }

    /**
     * Добавляет в массив добавляемых элементов поле с id записи, если нужно обновить её в дочерней таблице,
     * А также поле с ID экспонента
     * @param   array $data Массив с добавляемыми данными
     * @return  array
     * @since   1.1.3
     * @throws
     */
    private function addId(array $data): array
    {
        $id = $this->getId();
        if ($id !== 0) $data['exbID'] = $id;
        $table = $this->getTable('Banks', 'TableProjects');
        $table->load(array('exbID' => $id));
        if ($table->id != null) $data['id'] = $table->id;
        return $data;
    }

    /**
     * Получает ИД записи в таблице
     * @return  integer
     * @since   1.1.2
     * @throws
     */
    private function getId(): int
    {
        $tmp = JFactory::getApplication()->input->getInt('id', 0);
        $insertID = $this->getTable()->getDbo()->insertid();
        return ($tmp == 0) ? $insertID : $tmp;
    }

}