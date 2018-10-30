<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelSection extends AdminModel {
    public function getTable($name = 'Sections', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function import()
    {
        $to = JFactory::getApplication()->input->getInt('to', 0);
        $from = JFactory::getApplication()->input->getInt('from', 0);
        if ($from == 0) return false;
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName('*'))
            ->from($db->quoteName('#__prc_sections'))
            ->where("`priceID` = {$from}");
        $sections = $db->setQuery($query)->loadObjectList();
        $table = "#__prc_sections";
        $columns = array('priceID', 'title', 'state');
        $old = array(); //Массив с привязками старых ID разделов к новым
        $ids = array(); //Массив со старыми ID разделов
        foreach ($sections as $section)
        {
            $query = $db->getQuery(true);
            $query
                ->insert($db->quoteName($table))
                ->columns($db->quoteName($columns));
            $arr = array(
                $db->quote($to),
                $db->quote($section->title),
                $db->quote($section->state)
            );
            $query
                ->values(implode(', ', $arr));
            $db->setQuery($query)->execute();
            $old[$section->id] = $db->insertid();
            $ids[] = $section->id;
        }
        $ids = implode(', ', $ids);
        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName('*'))
            ->from($db->quoteName('#__prc_items'))
            ->where("`sectionID` IN ({$ids})");
        $items = $db->setQuery($query)->loadObjectList();
        $query = $db->getQuery(true);
        $query
            ->insert($db->quoteName("#__prc_items"))
            ->columns(
                $db->quoteName(
                    array(
                        'sectionID',
                        'unit',
                        'title_ru',
                        'title_en',
                        'price_rub',
                        'price_usd',
                        'price_eur',
                        'factor',
                        'state'
                    )
                )
            );
        foreach ($items as $item)
        {
            $arr = array(
                $db->quote($old[$item->sectionID]),
                $db->quote($item->unit),
                ($item->title_ru != null) ? $db->quote($item->title_ru) : 'NULL',
                ($item->title_en != null) ? $db->quote($item->title_en) : 'NULL',
                $db->quote($item->price_rub),
                $db->quote($item->price_usd),
                $db->quote($item->price_eur),
                $db->quote($item->factor),
                $db->quote($item->state)
            );
            $values = implode(', ', $arr);
            $query
                ->values($values);
        }
        return $db->setQuery($query)->execute();
    }


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.section', 'section', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.section.' . (int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.section.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
    	$nulls = array(); //Поля, которые NULL
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
            return $user->authorise('core.edit.state', $this->option . '.section.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/section.js';
    }
}