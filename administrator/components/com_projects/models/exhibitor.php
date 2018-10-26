<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelExhibitor extends AdminModel {
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
        $fields = array(
            'general' => array(
                'id' => $data['id'],
                'regID' => $data['regID'],
                'curatorID' => $data['curatorID'],
                'title_ru_full' => $data['title_ru_full'],
                'title_ru_short' => $data['title_ru_short'],
                'title_en' => $data['title_en'],
                'state' => $data['state'],
            ),
            'bank' => array(
                'inn' => $data['inn'],
                'kpp' => $data['kpp'],
                'rs' => $data['rs'],
                'ks' => $data['ks'],
                'bank' => $data['bank'],
                'bik' => $data['bik'],
            ),
            'contact' => array(
                'addr_legal_ru' => $data['addr_legal_ru'],
                'addr_legal_en' => $data['addr_legal_en'],
                'addr_fact' => $data['addr_fact'],
                'phone_1' => $data['phone_1'],
                'phone_2' => $data['phone_2'],
                'fax' => $data['fax'],
                'email' => $data['email'],
                'site' => $data['site'],
                'director_name' => $data['director_name'],
                'director_post' => $data['director_post'],
                'contact_person' => $data['contact_person'],
                'contact_data' => $data['contact_data'],
            )
        );
        $s1 = parent::save($fields['general']);

        $id = (JFactory::getApplication()->input->getInt('id', 0) == 0) ? $this->getTable()->getDbo()->insertid() : JFactory::getApplication()->input->getInt('id');
        $adv1 = $this->getFieldName('Banks', $id);
        $adv2 = $this->getFieldName('Addresses', $id);
        $fields['bank'][$adv1['field']] = $adv1['value'];
        $fields['contact'][$adv2['field']] = $adv2['value'];
        if ($id !== 0)
        {
            $fields['bank']['exbID'] = $id;
            $fields['contact']['exbID'] = $id;
        }

        $s2 = $this->saveData('Bank', $fields['bank']);
        $s3 = $this->saveData('Address', $fields['contact']);
        return $s1 && $s2 && $s3;
    }

    /*Сохраняем запись в дочернюю таблицу*/
    private function saveData(string $modelName, array $data): bool
    {
        $model = AdminModel::getInstance($modelName, 'ProjectsModel');
        $table = $model->getTable();
        $table->bind($data);
        $model->prepareTable($table);
        return $model->save($data);
    }

    /*Определяем вставляем запись в дочерней таблице или обновляем имеюющуюся*/
    private function getFieldName(string $tblName, int $id): array
    {
        $result = array();
        $table = $this->getTable($tblName, 'TableProjects');
        $table->load(array('exbID' => $id));
        if ($table->id == null)
        {
            $result['field'] = 'exbID';
            $result['value'] = $id;
        }
        else
        {
            $result['field'] = 'id';
            $result['value'] = $table->id;
        }
        return $result;
    }

    /*
     * Сохраняем банковские реквизиты
     * МЕТОД НЕ ИСПОЛЬЗУЕТСЯ. НУЖДАЕТСЯ В УДАЛЕНИИ.
     * */
    /*
    private function saveBank($data, int $id = 0)
    {
        $data['exbID'] = $id;
        unset($data['regID'], $data['curatorID'], $data['title_ru_full'], $data['title_ru_short'], $data['title_en'], $data['state'], $data['tags'], $data['id']);
        $db =& $this->getDbo();
        $table = $db->quoteName("#__prj_exp_bank");
        $arr = array(
            $db->quoteName('exbID') => $db->quote($id),
            $db->quoteName('inn') => (!empty($data['inn'])) ? $db->quote($data['inn']) : 'NULL',
            $db->quoteName('kpp') => (!empty($data['kpp'])) ? $db->quote($data['kpp']) : 'NULL',
            $db->quoteName('rs') => (!empty($data['rs'])) ? $db->quote($data['rs']) : 'NULL',
            $db->quoteName('ks') => (!empty($data['ks'])) ? $db->quote($data['ks']) : 'NULL',
            $db->quoteName('bank') => (!empty($data['bank'])) ? $db->quote($db->escape($data['bank'])) : 'NULL',
            $db->quoteName('bik') => (!empty($data['bik'])) ? $db->quote($data['bik']) : 'NULL',
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
    }*/
}