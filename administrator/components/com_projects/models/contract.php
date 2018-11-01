<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelContract extends AdminModel {
    public function getTable($name = 'Contracts', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
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

    public function getPrice()
    {
        $item = $this->getItem();
        $prjID = $item->prjID;
        $project = AdminModel::getInstance('Project', 'ProjectsModel')->getItem(array('id'=>$prjID));
        $values = $this->getPriceValues($item->id);
        $items = $this->getPriceItems($project->priceID, $values, $item->currency);
        return $items;
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
     * @param array $values Значения пунктов прайса из договора.
     * @param string $currency Валюта договора.
     * @return array
     * @since 1.2.0
     */
    private function getPriceItems(int $priceID, array $values, string $currency): array
    {
        $db =& $this->getDbo();
        $result = array();
        $query = $db->getQuery(true);
        $query
            ->select("`i`.`id`, `i`.`unit`")
            ->select("IFNULL(`i`.`title_ru`,`i`.`title_en`) as `title`")
            ->select("`i`.`price_{$currency}` as `cost`")
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
            $arr['unit'] = ProjectsHelper::getUnit($item->unit);
            $arr['value'] = $values[$item->id]['value'];
            $result[] = $arr;
        }
        return $result;
    }

    private function savePrice(): bool
    {
        $contractID = JFactory::getApplication()->input->getInt('id');
        $post = $_POST['jform']['price'];
        if (empty($post)) return true;
        $model = AdminModel::getInstance('Ctritem', 'ProjectsModel');
        $table = $model->getTable();
        foreach ($post as $itemID => $value) {
            $pks = array('contractID' => $contractID, 'itemID' => $itemID);
            $row = $model->getItem($pks);
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
        return true;
    }

    public function save($data)
    {
        $s1 = parent::save($data);
        if (!$s1)
        {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::sprintf('COM_PROJECTS_ERROR_DUPLICATE_CONTRACT'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_projects&view=contract&layout=edit'));
            jexit();
        }
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
    	$nulls = array('status', 'dat'); //Поля, которые NULL
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
}