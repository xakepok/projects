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
        $items_model = AdminModel::getInstance('Ctritem', 'ProjectsModel');
        $ctritem = $items_model->getItem(array('itemID' => $item->itemID));
        $nv = array();
        $nv['id'] = $ctritem->id ?? null;
        $nv['contractID'] = $data['contractID'];
        $nv['itemID'] = $data['itemID'];
        $nv['columnID'] = ProjectsHelper::getActivePriceColumn($contract->id);
        $nv['factor'] = $ctritem->factor ?? 1;
        $nv['markup'] = $ctritem->markup ?? 1;
        $nv['value2'] = $ctritem->value2 ?? null;
        $nv['fixed'] = $ctritem->fixed ?? null;
        $nv['value'] = $ctritem->value - $stand->square;
        if ($nv['value'] > 0) {
            $items_model->save($nv);
        }
        else
        {
            $items_model->delete($ctritem->id);
        }
        ProjectsHelper::addEvent(array('action' => 'delete', 'section' => 'stand', 'itemID' => $item->id, 'old_data' => $item));
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
        if ($contractID != null) {
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $contract = $cm->getItem($contractID);
            $coExps = ProjectsHelper::getContractCoExp($contract->expID, $contract->prjID);
            if (count($coExps) == 0) {
                $form->removeField('delegate');
            }
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
            $items = AdminModel::getInstance('Ctritem', 'ProjectsModel');
            $item = $items->getItem(array('itemID' => $data['itemID'], 'contractID' => $data['contractID']));
            $nv = array();
            $nv['id'] = $item->id ?? null;
            $nv['contractID'] = $data['contractID'];
            $nv['itemID'] = $data['itemID'];
            $nv['columnID'] = ProjectsHelper::getActivePriceColumn($contract->id);
            $nv['factor'] = $item->factor ?? 1;
            $nv['markup'] = $item->markup ?? 1;
            $nv['value2'] = $item->value2 ?? null;
            $nv['fixed'] = $item->fixed ?? null;
            $nv['value'] = ($item->id != null) ? $item->value + $stand->square : $stand->square;
            $items->save($nv);
            $action = 'add';
            $old = null;
        }
        if ($data['id'] != null) {
            $item = parent::getItem($data['id']);
            $old = $item;
            $arr['contractID'] = $data['contractID'];
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $contract = $cm->getItem($data['contractID']);
            $sm = AdminModel::getInstance('Catalog', 'ProjectsModel');
            $stand_old = $sm->getItem($item->catalogID);
            $stand_new = $sm->getItem($data['catalogID']);
            $items_model = AdminModel::getInstance('Ctritem', 'ProjectsModel');
            if ($item->itemID != $data['itemID'] && $stand_old->id != $stand_new->id) //Если меняются и пункт прайса, и стенд
            {
                JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_CANT_EDIT_STAND_TYPE_AND_ITEM'), 'error');
                return false;
            }

            //Уведомляем техдиеркцию о изменении стенда
            if ($stand_old->id != $stand_new->id && $item->itemID == $data['itemID']) {
                if ($contract->number != null) {
                    $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_DG_EDITED', $contract->number, $stand_old->number, $stand_new->number);
                } else {
                    $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_SD_EDITED', $contract->id, $stand_old->number, $stand_new->number);
                }
                $arr['managerID'] = 400;
                $this->_createTodo($arr, true);
            }
            //Обноялвем поля в таблице заказанных услуг
            $nv = array();
            if ($item->itemID == $data['itemID'] && $stand_old->id != $stand_new->id) //Если пункт прайс-листа не меняется, а меняется стенд
            {
                $ctritem = $items_model->getItem(array('itemID' => $data['itemID'], 'contractID' => $data['contractID']));
                $nv['id'] = $ctritem->id;
                $nv['value'] = $ctritem->value - $stand_old->square + $stand_new->square;
                $items_model->save($nv);
            }
            if ($item->itemID != $data['itemID'] && $stand_old->id == $stand_new->id) //Если пункт прайс-листа меняется, но не меняется стенд
            {
                $ctritem = $items_model->getItem(array('itemID' => $item->itemID, 'contractID' => $data['contractID']));
                $nv['id'] = $ctritem->id;
                $nv['value'] = $ctritem->value - $stand_old->square;
                if ($nv['value'] > 0) {
                    $items_model->save($nv);
                }
                else
                {
                    $items_model->delete($ctritem->id);
                }
                $ctritem = $items_model->getItem(array('itemID' => $data['itemID'], 'contractID' => $data['contractID']));
                $nv = array();
                $nv['id'] = $ctritem->id ?? null;
                $nv['contractID'] = $data['contractID'];
                $nv['itemID'] = $data['itemID'];
                $nv['columnID'] = ProjectsHelper::getActivePriceColumn($data['contractID']);
                $nv['factor'] = $ctritem->factor ?? 1;
                $nv['markup'] = $ctritem->markup ?? 1;
                $nv['value2'] = $ctritem->value2 ?? null;
                $nv['fixed'] = $ctritem->fixed ?? null;
                $nv['value'] = ($ctritem->id != null) ? $ctritem->value + $stand_new->square : $stand_new->square;
                $items_model->save($nv);
            }
            $action = 'edit';
            $itemID = $data['id'];
        }
        if ($data['scheme'] == '-1') $data['scheme'] = NULL;
        if ($data['status'] == '3')
        {
            $this->_createTodo($data);
        }
        $s = parent::save($data);
        if ($action == 'add') {
            $itemID = parent::getDbo()->insertid();
            $data['id'] = $itemID;
        }
        ProjectsHelper::addEvent(array('action' => $action, 'section' => 'stand', 'itemID' => $itemID, 'params' => $data, 'old_data' => $old));
        return $s;
    }

    protected function prepareTable($table)
    {
        $nulls = array('catalogID', 'itemID', 'number', 'freeze', 'comment', 'scheme', 'delegate'); //Поля, которые NULL
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