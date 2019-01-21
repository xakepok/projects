<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelStand extends AdminModel {
    public function getTable($name = 'Stands', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getStandTitle(int $id): string
    {
        $model = AdminModel::getInstance('Contract', 'ProjectsModel');
        $session = JFactory::getSession();
        if ($id === 0) $contractID = $session->get('contractID');
        $contract = $model->getItem($contractID ?? $id);
        if (!empty($contract->number))
        {
            $dat = new JDate($contract->dat);
            $title = JText::sprintf('COM_PROJECTS_BLANK_STAND_FOR_CONTRACT', $contract->number, $dat->format("d.m.Y"));
        }
        else
        {
            $model = AdminModel::getInstance('Exhibitor', 'ProjectsModel');
            $exhibitor = $model->getItem($contract->expID);
            $title = ProjectsHelper::getExpTitle($exhibitor->title_ru_short, $exhibitor->title_ru_full, $exhibitor->title_en);
            $title = JText::sprintf('COM_PROJECTS_BLANK_STAND_FOR_SDELKA', $title);
        }
        return $title;
    }

    public function delete(&$pks)
    {
        $item = parent::getItem($pks);
        $data['contractID'] = $item->contractID;
        $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
        $contract = $cm->getItem($item->contractID);
        $sm = AdminModel::getInstance('Catalog', 'ProjectsModel');
        $stand = $sm->getItem($item->catalogID);
        if ($contract->number != null)
        {
            $data['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_DG_REMOVE', $contract->number, $stand->number);
        }
        else
        {
            $data['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_SD_REMOVE', $contract->id, $stand->number);
        }
        $data['managerID'] = 400;
        $this->_createTodo($data, true);
        return parent::delete($pks);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $item->title = $this->getStandTitle($item->contractID ?? 0);
        return $item;
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
        $session = JFactory::getSession();
        $contractID = $form->getValue('contractID') ?? $session->get('contractID');
        $dir = ($contractID != null && JFolder::exists(JPATH_ROOT."/images/contracts/{$contractID}")) ? JPATH_ROOT."/images/contracts/{$contractID}" : JPATH_ROOT."/images/contracts";
        $form->setFieldAttribute('scheme', 'directory', $dir);

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
        if ($data['id'] == null)
        {
            $arr['contractID'] = $data['contractID'];
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $contract = $cm->getItem($data['contractID']);
            $sm = AdminModel::getInstance('Catalog', 'ProjectsModel');
            $stand = $sm->getItem($data['catalogID']);
            if ($contract->number != null)
            {
                $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_DG_ADDED', $contract->number, $stand->number);
            }
            else
            {
                $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_SD_ADDED', $contract->id, $stand->number);
            }
            $arr['managerID'] = 400;
            $this->_createTodo($arr, true);
        }
        if ($data['id'] != null) {
            $item = parent::getItem($data['id']);
            $arr['contractID'] = $data['contractID'];
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $contract = $cm->getItem($data['contractID']);
            $sm = AdminModel::getInstance('Catalog', 'ProjectsModel');
            $stand_old = $sm->getItem($item->catalogID);
            $stand_new = $sm->getItem($data['catalogID']);
            if ($contract->number != null)
            {
                $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_DG_EDITED', $contract->number, $stand_old->number, $stand_new->number);
            }
            else
            {
                $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_SD_EDITED', $contract->id, $stand_old->number, $stand_new->number);
            }
            $arr['managerID'] = 400;
            $this->_createTodo($arr, true);
        }
        if ($data['scheme'] == '-1') $data['scheme'] = NULL;
        if ($data['status'] == '3')
        {
            $this->_createTodo($data);
        }
        $s = parent::save($data);
        return $s;
    }

    /**
     * Скрывает стенды из выборки при сортировке
     * @param int $id ID стенда, который остаётся показываться
     * @param int $contractID ID сделки
     * @throws
     * @since 1.0.5.7
     */
    private function dontShow(int $id, int $contractID): void
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->update("`#__prj_stands`")
            ->set("`show` = 0")
            ->where("`contractID` = {$contractID}")
            ->where("`id` != {$id}");
        $db->setQuery($query)->execute();
    }

    protected function prepareTable($table)
    {
        $nulls = array('catalogID', 'itemID', 'number', 'freeze', 'comment', 'scheme'); //Поля, которые NULL
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
     * @param bool $is_notify Создание уведомления
     * @return bool
     * @throws Exception
     * @since 1.3.0.9
     */
    protected function _createTodo(array $data, bool $is_notify = false): bool
    {
        $arr = array();
        $arr['id'] = NULL;
        $arr['is_notify'] = (int) $is_notify;
        $arr['dat'] = date('Y-m-d', strtotime(' +1 weekdays'));
        $arr['contractID'] = $data['contractID'];

        $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
        $contract = $cm->getItem($data['contractID']);
        $arr['managerID'] = $data['managerID'] ?? $contract->managerID;
        $sm = AdminModel::getInstance('Catalog', 'ProjectsModel');
        $stand = $sm->getItem($data['catalogID']);
        if ($contract->number != null)
        {
            $arr['task'] = $data['task'] ?? JText::sprintf('COM_PROJECT_TASK_ACCEPT_SCHEME', $stand->number, $contract->number);
        }
        else
        {
            $arr['task'] = $data['task'] ?? JText::sprintf('COM_PROJECT_TASK_ACCEPT_SCHEME_SD', $stand->number, $contract->id);
        }
        $arr['user_open'] = JFactory::getUser()->id;
        $arr['state'] = 0;
        $model = AdminModel::getInstance('Todo', 'ProjectsModel');
        $table = $model->getTable();
        $table->bind($arr);
        return $model->save($arr);
    }
}