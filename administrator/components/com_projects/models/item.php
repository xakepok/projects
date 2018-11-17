<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelItem extends AdminModel {
    public function getTable($name = 'Items', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $item->column_1 = (int) ($item->column_1 * 100 - 100);
        $item->column_2 = (int) ($item->column_2 * 100 - 100);
        $item->column_3 = (int) ($item->column_3 * 100 - 100);
        return $item;
    }

    public function save($data)
    {
        $data['column_1'] = (float) (100 + $data['column_1']) / 100;
        $data['column_2'] = (float) (100 + $data['column_2']) / 100;
        $data['column_3'] = (float) (100 + $data['column_3']) / 100;
        return parent::save($data);
    }

    /**
     * Возвращает название прайс-листа для показа в заголовке
     * @return string Название прайс-листа
     * @since 1.2.6
     */
    public function getPriceName(): string
    {
        $item = parent::getItem();
        if ($item->id == null) return '';
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`p`.`title`")
            ->from("`#__prc_sections` as `s`")
            ->leftJoin("`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`")
            ->where("`s`.`id` = {$item->sectionID}");
        return $db->setQuery($query)->loadResult();
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.item', 'item', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.item.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.item.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
    	$nulls = array('title_ru', 'title_en', 'unit_2'); //Поля, которые NULL
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
            return $user->authorise('core.edit.state', $this->option . '.item.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/item.js';
    }
}