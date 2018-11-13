<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelContract extends AdminModel {
    public function getTable($name = 'Contracts', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $item->discount = (int) (100 - $item->discount * 100); //Переводим значение скидки в проценты
        $item->markup = (int) ($item->markup * 100 - 100); //Переводим значение наценки в проценты
        return $item;
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.contract', 'contract', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.contract.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    /**
     * Возвращает поля для заполнения из прайса для текущего договора
     * @return array
     * @since 1.2.0
     */
    public function getPrice(): array
    {
        $item = $this->getItem();
        $prjID = $item->prjID;
        if ($prjID == null) return array();
        $project = AdminModel::getInstance('Project', 'ProjectsModel')->getItem(array('id'=>$prjID));
        $values = $this->getPriceValues($item->id);
        $items = $this->getPriceItems($project->priceID, $project->columnID, $values, $item->currency);
        return $items;
    }

    public function publish(&$pks, $value = 1)
    {
        if ($value == 2) {
            foreach ($pks as $pk) {
                $item = $this->getItem($pk);
                if ($item->status == '0' || $item->status == '1' || $item->status == '5' || $item->status == '6') parent::publish($pk, $value);
            }
            return true;
        }
        return parent::publish($pks, $value);
    }

    public function save($data)
    {
        $data['discount'] = (float) (100 - $data['discount']) / 100; //Переводим значение скидки в коэффициент
        $data['markup'] = (float) (100 + $data['markup']) / 100; //Переводим значение наценки в коэффициент
        $this->saveHistory($data['id'], $data['status']);
        $s1 = parent::save($data);
        $s2 = $this->savePrice();
        return $s1 && $s2;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.contract.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
    	$nulls = array('status', 'dat', 'groupID', 'managerID', 'number', 'parentID'); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
    	if ($table->status != '5' && $table->status != '6') $table->parentID = NULL;
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.contract.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/contract.js';
    }

    /**
     * Сохраняет действие пользователя в историю
     * @param int $contractID ID контракта
     * @param int $status новый статус договора
     * @since 1.2.6
     * @throws
     */
    private function saveHistory(int $contractID, int $status): void
    {
        $managerID = JFactory::getUser()->id;
        $data = array('dat' => date("Y-m-d H:i:s"), 'contractID' => $contractID, 'managerID' => $managerID, 'status' => $status);
        $model = AdminModel::getInstance('History','ProjectsModel');
        $old_status = $model->getLastStatus($contractID);
        if ($old_status == $status) return;
        $table = $model->getTable();
        $table->bind($data);
        $model->save($data);
    }

    /**
     * @param int $contractID ID договора
     * @return array
     * @since 1.2.0
     */
    private function getPriceValues(int $contractID): array
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_contract_items`")
            ->where("`contractID` = {$contractID}");
        return $db->setQuery($query)->loadAssocList('itemID');
    }

    /**
     * Возвращает пункты указанного прайс-листа.
     * @param int $priceID ID прайс-листа.
     * @param int $columnID ID колонки прайс-листа, из которой брать цены.
     * @param array $values Значения пунктов прайса из договора.
     * @param string $currency Валюта договора.
     * @return array
     * @since 1.2.0
     */
    private function getPriceItems(int $priceID, int $columnID, array $values, string $currency): array
    {
        $db =& $this->getDbo();
        $result = array();
        $query = $db->getQuery(true);
        $query
            ->select("`i`.`id`, `i`.`unit`, `unit_2` as `isUnit2`, IFNULL(`i`.`unit_2`,'TWO_NOT_USE') as `unit_2`")
            ->select("IFNULL(`i`.`title_ru`,`i`.`title_en`) as `title`")
            ->select("`i`.`price_{$currency}_u1_c{$columnID}` as `cost`")
            ->select("`i`.`price_{$currency}_u2_c{$columnID}` as `cost2`")
            ->from("`#__prc_items` as `i`")
            ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `i`.`sectionID`")
            ->leftJoin("`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`")
            ->where("`p`.`id` = {$priceID}");
        $items = $db->setQuery($query)->loadObjectList();
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $arr['cost'] = sprintf("%s %s", $item->cost, $currency);
            $arr['cost2'] = sprintf("%s %s", $item->cost2, $currency);
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['unit2'] = ProjectsHelper::getUnit($item->unit_2);
            $arr['isUnit2'] = $item->isUnit2;
            $arr['value'] = $values[$item->id]['value'];
            $arr['value2'] = $values[$item->id]['value2'];
            $arr['factor'] = $values[$item->id]['factor'];
            $arr['factor2'] = $values[$item->id]['factor2'];
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Сохраняет значения для полей из прайс-листа для текущего договора
     * @return bool
     * @throws Exception
     * @since 1.2.0
     */
    private function savePrice(): bool
    {
        $contractID = JFactory::getApplication()->input->getInt('id');
        $post = $_POST['jform']['price'];
        if (empty($post)) return true;
        $model = AdminModel::getInstance('Ctritem', 'ProjectsModel');
        $table = $model->getTable();
        foreach ($post[1]['item'] as $itemID => $value) {
            $pks = array('contractID' => $contractID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
            $arr = array();
            $arr['contractID'] = $contractID;
            $arr['itemID'] = $itemID;
            $arr['value'] = $value;
            if ($row->id != null)
            {
                if ($value == '')
                {
                    $model->delete($row->id);
                }
                else
                {
                    $arr['id'] = $row->id;
                    $table->bind($arr);
                    $model->save($arr);
                }
            }
            else {
                $arr['id'] = null;
                $table->bind($arr);
                $model->save($arr);
            }
        }
        foreach ($post[2]['item'] as $itemID => $value) {
            $pks = array('contractID' => $contractID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
            $arr = array();
            $arr['contractID'] = $contractID;
            $arr['itemID'] = $itemID;
            $arr['value2'] = $value;
            if ($row->id != null)
            {
                if ($value == '')
                {
                    $model->delete($row->id);
                }
                else
                {
                    $arr['id'] = $row->id;
                    $table->bind($arr);
                    $model->save($arr);
                }
            }
            else {
                $arr['id'] = null;
                $table->bind($arr);
                $model->save($arr);
            }
        }
        foreach ($post[1]['factor'] as $itemID => $value) {
            $pks = array('contractID' => $contractID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
            $arr = array();
            $arr['contractID'] = $contractID;
            $arr['itemID'] = $itemID;
            $arr['factor'] = $value;
            if ($row->id != null)
            {
                if ($value == '')
                {
                    $model->delete($row->id);
                }
                else
                {
                    $arr['id'] = $row->id;
                    $table->bind($arr);
                    $model->save($arr);
                }
            }
            else {
                $arr['id'] = null;
                $table->bind($arr);
                $model->save($arr);
            }
        }
        foreach ($post[2]['factor'] as $itemID => $value) {
            $pks = array('contractID' => $contractID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
            $arr = array();
            $arr['contractID'] = $contractID;
            $arr['itemID'] = $itemID;
            $arr['factor2'] = $value;
            if ($row->id != null)
            {
                if ($value == '')
                {
                    $model->delete($row->id);
                }
                else
                {
                    $arr['id'] = $row->id;
                    $table->bind($arr);
                    $model->save($arr);
                }
            }
            else {
                $arr['id'] = null;
                $table->bind($arr);
                $model->save($arr);
            }
        }
        return true;
    }

}