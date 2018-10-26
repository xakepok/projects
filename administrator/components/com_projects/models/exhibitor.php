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
        $table = $this->getTable('Banks', 'TableProjects');
        $table->load(array('exbID' => $item->id));
        $item->inn = $table->inn;
        $item->kpp = $table->kpp;
        $item->rs = $table->rs;
        $item->ks = $table->ks;
        $item->bank = $table->bank;
        $item->bik = $table->bik;
        $table = $this->getTable('Addresses', 'TableProjects');
        $table->load(array('exbID' => $item->id));
        $item->addr_legal_ru = $table->addr_legal_ru;
        $item->addr_legal_en = $table->addr_legal_en;
        $item->addr_fact = $table->addr_fact;
        $item->phone_1 = $table->phone_1;
        $item->phone_2 = $table->phone_2;
        $item->fax = $table->fax;
        $item->email = $table->email;
        $item->site = $table->site;
        $item->director_name = $table->director_name;
        $item->director_post = $table->director_post;
        $item->contact_person = $table->contact_person;
        $item->contact_data = $table->contact_data;
        return $item;
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
        $fields = $this->filterFields($this->getTable(), $data);
        $s1 = parent::save($fields);

        $data = $this->addId($data);

        $s2 = $this->saveData('Bank', $data);
        $s3 = $this->saveData('Address', $data);
        return $s1 && $s2 && $s3;
    }

    /*Сохраняем запись в дочернюю таблицу*/
    private function saveData(string $modelName, array $data): bool
    {
        $model = AdminModel::getInstance($modelName, 'ProjectsModel');
        $table = $model->getTable();
        $data = $this->filterFields($table, $data);
        $table->bind($data);
        $model->prepareTable($table);
        return $model->save($data);
    }

    /*Отсиеваем ненужные поля перед привязкой к таблице*/
    private function filterFields(object $table, array $data): array
    {
        foreach ($data as $field => $value) {
            if (!property_exists($table, $field)) unset($data[$field]);
        }
        return $data;
    }

    /*Добавляем поле с id записи, если нужно обновить её в дочерней таблице, а не добавить*/
    private function addId(array $data): array
    {
        $id = $this->getId();
        if ($id !== 0) $data['exbID'] = $id;
        $table = $this->getTable('Banks', 'TableProjects');
        $table->load(array('exbID' => $id));
        if ($table->id != null) $data['id'] = $table->id;
        return $data;
    }

    /*Получаем ИД записи в таблице*/
    private function getId(): int
    {
        return (JFactory::getApplication()->input->getInt('id', 0) == 0) ? $this->getTable()->getDbo()->insertid() : JFactory::getApplication()->input->getInt('id', 0);
    }

}