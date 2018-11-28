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
        if ($item->id != null)
        {
            $item->files = $this->loadFiles();
            $item->stands = $this->getStands();
        }
        return $item;
    }

    public function getStands(): array
    {
        $item = parent::getItem();
        if ($item->id == null) return array();
        $items = ProjectsHelper::getContractStands($item->id);
        $result = array();
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['number'] = $item->number;
            $arr['freeze'] = $item->freeze;
            $arr['comment'] = $item->comment;
            $arr['tip'] = ProjectsHelper::getStandType($item->tip);
            $arr['status'] = ProjectsHelper::getStandStatus($item->status);
            $arr['action'] = JRoute::_("index.php?option=com_projects&amp;view=stand&amp;layout=edit&amp;id={$item->id}");
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Возвращает список заданий из планировщика для указанной сделки
     * @return array
     * @since 1.2.9.7
     */
    public function getTodos(): array
    {
        $result = array();
        $item = parent::getItem();
        if ($item->id == null) return $result;
        $todos = ProjectsHelper::getContractTodo($item->id);
        $return = base64_encode(JUri::base()."index.php?option=com_projects&view=contract&layout=edit&id={$item->id}");
        foreach ($todos as $todo) {
            $arr = array();
            $arr['action'] = JRoute::_("index.php?option=com_projects&amp;view=todo&amp;id={$todo->id}&amp;layout=edit&amp;return={$return}");
            $arr['id'] = $todo->id;
            $arr['dat'] = $todo->dat;
            $arr['task'] = $todo->task;
            $arr['user'] = $todo->user;
            $arr['result'] = $todo->result;
            $arr['state'] = $todo->state;
            $arr['expired'] = (bool) $todo->expired;
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Возвращает название экспонента и название проекта, по которому проводится сделка
     * Используется в Title вьюшки
     * @return string
     * @since 1.2.9.5
     */
    public function getTitle():string
    {
        $item = parent::getItem();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`number`, `c`.`status`, `e`.`title_ru_short` as `exponent`, `p`.`title` as `project`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->where("`c`.`id` = {$item->id}");
        $title = $db->setQuery($query, 0, 1)->loadObject();
        return ($title->status =='1') ? JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_ACCEPT', $title->number, $title->exponent, $title->project) : JText::sprintf('COM_PROJECTS_TITLE_CONTRACT', $title->exponent, $title->project);
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
    public function getPrice($pks = null): array
    {
        $item = $this->getItem($pks);
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
        if (!isset($data['managerID'])) $data['managerID'] = JFactory::getUser()->id;
        $s1 = parent::save($data);
        if ($data['id'] == null) $data['id'] = $this->_db->insertid();
        $this->saveHistory($data['id'], $data['status']);
        $s2 = $this->savePrice();
        $s3 = $this->saveFiles();
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
    	$nulls = array('status', 'dat', 'number', 'parentID'); //Поля, которые NULL
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

    public function resetAmount(int $contractID): void
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->update("`#__prj_contracts`")
            ->set("`amount` = 0")
            ->where("`id` = {$contractID}");
        $db->setQuery($query)->execute();
        $query = $db->getQuery(true);
        $query
            ->update("`#__prj_contract_items`")
            ->set("`fixed` = NULL")
            ->where("`contractID` = {$contractID}");
        $db->setQuery($query)->execute();
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/contract.js';
    }

    /**
     * Расчёт и фиксация цены сделки
     * @param int $contractID ID сделки
     * @return void
     * @since 1.2.9.2
     */
    public function calculate(int $contractID): void
    {
        $price = $this->getPrice($contractID);
        $sum = 0;
        $keys = array();
        foreach ($price as $item)
        {
            if ($item['fixed'] != null) continue;
            array_push($keys, $item['id']);
            $sum += $item['cost_clean'] * $item['value'] * $item['factor'];
            if ($item['isUnit2'] != null) $sum += $item['cost2_clean'] * $item['value2'] * $item['factor2'];
        }
        $this->fixedAmount($contractID, $sum, $keys);
    }

    /**
     * Фиксация цены сделки
     * @param int $contractID ID сделки
     * @param float $amount Сумма сделки
     * @param array $keys Массив с ключами таблицы заказа сделки
     * @since 1.2.9.2
     */
    private function fixedAmount(int $contractID, float $amount, array $keys): void
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->update("`#__prj_contracts`")
            ->set("`amount` = `amount` + {$amount}")
            ->where("`id` = {$contractID}");
        $db->setQuery($query)->execute();
        $query = $db->getQuery(true);
        $keys = implode(', ', $keys);
        $query
            ->update("`#__prj_contract_items`")
            ->set("`fixed` = 1")
            ->where("`id` IN ($keys)");
        $db->setQuery($query)->execute();
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
        $item = parent::getItem();
        $activeColumn = ProjectsHelper::getActivePriceColumn($item->id);
        $db =& $this->getDbo();
        $result = array();
        $query = $db->getQuery(true);
        $query
            ->select("`i`.`id`, `i`.`unit`, `unit_2` as `isUnit2`, IFNULL(`i`.`unit_2`,'TWO_NOT_USE') as `unit_2`, `i`.`is_factor`, `i`.`is_markup`, `i`.`sectionID`")
            ->select("IFNULL(`i`.`title_ru`,`i`.`title_en`) as `title`")
            ->select("`i`.`price_{$currency}` as `price`")
            ->select("`i`.`column_1`, `i`.`column_2`, `i`.`column_3`")
            ->select("`s`.`title` as `section`")
            ->from("`#__prc_items` as `i`")
            ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `i`.`sectionID`")
            ->leftJoin("`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`")
            ->where("`p`.`id` = {$priceID}");
        $items = $db->setQuery($query)->loadObjectList();
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['section_id'] = $item->sectionID;
            $arr['title'] = $item->title;
            $pc = ($activeColumn != $values[$item->id]['columnID'] && !empty($values[$item->id]['columnID'])) ? $values[$item->id]['columnID'] : $activeColumn;
            $pc = "column_{$pc}";
            $cost = $item->price * $item->$pc;
            $arr['cost'] = sprintf("%s %s", number_format($cost, 2, '.', " "), $currency);
            $arr['currency'] = $currency;
            $arr['cost_clean'] = $cost;
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['unit2'] = ProjectsHelper::getUnit($item->unit_2);
            $arr['isUnit2'] = $item->isUnit2;
            $arr['value'] = $values[$item->id]['value'];
            $arr['value2'] = $values[$item->id]['value2'];
            $arr['is_markup'] = $item->is_markup;
            $arr['markup'] = (float) ($values[$item->id]['markup'] != null) ? (float) $values[$item->id]['markup'] * 100 - 100 : 0;
            $arr['is_factor'] = $item->is_factor;
            $arr['factor'] = (int) ($values[$item->id]['factor'] != null) ? 100 - $values[$item->id]['factor'] * 100 : 0;
            $arr['fixed'] = ($activeColumn != $values[$item->id]['columnID'] && !empty($values[$item->id]['columnID']) && !ProjectsHelper::canDo('core.admin')) ? true : false;
            $sum = 0;
            if ($values[$item->id]['value']) $sum += $values[$item->id]['value'] * round($cost, 2);
            if ($values[$item->id]['value2'] != null) $sum = $sum * round($values[$item->id]['value2'], 2);
            if ($values[$item->id]['factor'] != null) $sum = $sum * $values[$item->id]['factor'];
            if ($values[$item->id]['markup'] != null) $sum = $sum * $values[$item->id]['markup'];
            $arr['sum'] = round($sum, 2);
            if (!isset($result[$item->section])) $result[$item->section] = array();
            $result[$item->section][] = $arr;
        }
        return $result;
    }

    /**
     * Возвращает список файлов в указанной сделке
     * @return array
     * @throws Exception
     * @since 1.3.0.0
     */
    private function loadFiles(): array
    {
        $contractID = JFactory::getApplication()->input->getInt('id', 0);
        if ($contractID == 0) return array();
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`path`")
            ->from("`#__prj_contract_files`")
            ->where("`contractID` = {$contractID}");
        return $db->setQuery($query)->loadColumn();
    }

    /**
     * Сохраняет список файлов в сделке
     * @return bool
     * @since 1.3.0.0
     * @throws
     */
    private function saveFiles(): bool
    {
        $post = $_POST['jform']['files'];
        $model = AdminModel::getInstance('Files', 'ProjectsModel');
        $table = $model->getTable();
        $contractID = JFactory::getApplication()->input->getInt('id');
        $alreadyFiles = $this->loadFiles(); //Список файлов, который на данный момент в таблице у этой сделке
        foreach ($post as $path)
        {
            if (empty($path)) continue;
            $pks = array('contractID' => $contractID, 'path' => $path);
            $row = $model->getItem($pks);
            $data = array(
                'contractID' => $contractID,
                'path' => $path,
                'dat' => date("Y-m-d H:i:s"),
                'id' => ($row->id != null) ? $row->id : NULL
            );
            $table->bind($data);
            $s = $model->save($data);
            foreach ($alreadyFiles as $i => $file)
            {
                if ($path == $file)
                {
                    unset($alreadyFiles[$i]);
                    break;
                }
            }
            if (!$s) exit(var_dump($model->getErrors()));
        }
        foreach ($alreadyFiles as $file)
        {
            $pks = array('contractID' => $contractID, 'path' => $file);
            $row = $model->getItem($pks);
            $model->delete($row->id);
        }
        return true;
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
        $columnID = ProjectsHelper::getActivePriceColumn($contractID);

        foreach ($post as $itemID => $values) {
            $pks = array('contractID' => $contractID, 'columnID' => $columnID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
            $arr = array();
            $arr['id'] = ($row->id != null) ? $row->id : null;
            $arr['contractID'] = $contractID;
            $arr['itemID'] = $itemID;
            $arr['columnID'] = $columnID;
            foreach ($values as $field => $value)
            {
                if ($field == 'factor' && $value != null) $arr['factor'] = (float) (100 - $value) / 100;
                if ($field == 'markup' && $value != null) $arr['markup'] = (float) (100 + $value) / 100;
                if ($field == 'value' && $value != null) $arr['value'] = $value;
                if ($field == 'value2' && $value != null) $arr['value2'] = $value;
            }
            if (!isset($arr['markup'])) $arr['markup'] = NULL;
            if (!isset($arr['value2'])) $arr['value2'] = NULL;
            if (!isset($arr['value'])) continue;
            if ($arr['value'] == 0 && $row->id != null)
            {
                $model->delete($row->id);
            }

            if (!$table->bind($arr))
            {
                exit(var_dump($arr));
            }
            if (!$model->save($arr))
            {
                exit(var_dump($model->getErrors()));
            }
            unset($arr);
        }
        return true;
    }

}