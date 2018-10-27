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
        $item = parent::getItem($pk);
        $bank = AdminModel::getInstance('Bank', 'ProjectsModel')->getItem(array('exbID' => $item->id));
        $address = AdminModel::getInstance('Address', 'ProjectsModel')->getItem(array('exbID' => $item->id));
        unset($item->_errors, $bank->exbID, $bank->id, $bank->_errors, $address->exbID, $address->id, $address->_errors);
        return (object) array_merge((array) $item, (array) $bank, (array) $address);
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
        return $s1 && $s2 && $s3;
    }

    /**
     * Сохраняем запись в дочернюю таблицу.
     * @param   string  $modelName  Краткое название модели.
     * @param   array   $data   Массив с добавляемыми данными.
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
     * @param   array   $data   Массив с добавляемыми данными
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