<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelSection extends AdminModel
{
    public function getTable($name = 'Sections', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    /**
     * Выполняет испорт разделов и полей прайс-листа из имеющегося в пустой.
     * @param int $from ID прайс-листа, откуда копировать секции и пункты.
     * @param int $to ID прайс-листа, куда копировать секции и пункты.
     * @return  boolean.
     * @throws Exception.
     * @since 1.1.9
     */
    public function import(int $from, int $to): bool
    {
        $sections = $this->getPriceSections($from);
        $ids = $this->insertPriceSections($sections, $to);
        $items = $this->getPriceItems(array_keys($ids));
        return $this->insertPriceItems($items, $ids);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.section', 'section', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form)) {
            return false;
        }
        $id = JFactory::getApplication()->input->get('id', 0);
        $user = JFactory::getUser();
        if ($id != 0 && (!$user->authorise('core.edit.state', $this->option . '.section.' . (int)$id))
            || ($id == 0 && !$user->authorise('core.edit.state', $this->option)))
            $form->setFieldAttribute('state', 'disabled', 'true');

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.section.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
        $nulls = array(); //Поля, которые NULL
        foreach ($nulls as $field) {
            if (!strlen($table->$field)) $table->$field = NULL;
        }
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', $this->option . '.section.' . (int)$record->id);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/section.js';
    }

    /**
     * Выполняет вставку секций в новый прайс-лист.
     * @param array $sections Массив с объектами секций.
     * @param int $to ID прайс-листа, куда вставлять секции.
     * @return array    Массив с привязками старых ID разделов к новым.
     * @since 1.1.9
     */
    private function insertPriceSections(array $sections, int $to): array
    {
        $ids = array(); //Массив с привязками старых ID разделов к новым
        $db =& $this->getDbo();
        foreach ($sections as $section) {
            $query = $db->getQuery(true);
            $query
                ->insert("#__prc_sections")
                ->columns(array('priceID', 'title', 'state'));
            $arr = array(
                $db->quote($to),
                $db->quote($section->title),
                $db->quote($section->state)
            );
            $query
                ->values(implode(', ', $arr));
            $db->setQuery($query)->execute();
            $ids[$section->id] = $db->insertid();
        }
        return $ids;
    }

    /**
     * Выполняет вставку пунктов в новый прайс-лист.
     * @param array $items Массив с пунктами секций.
     * @param array $ids Массив с привязкой ID старых секций и новых.
     * @return bool.
     * @since 1.1.9
     */
    private function insertPriceItems(array $items, array $ids): bool
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->insert("#__prc_items")
            ->columns(array('sectionID', 'application', 'unit', 'unit_2', 'title_ru', 'title_en', 'price_rub', 'price_usd', 'price_eur',
                'column_1', 'column_2', 'column_3', 'is_factor', 'is_markup', 'is_cleaning', 'is_sq', 'is_water',
                'badge', 'in_stat', 'is_electric', 'is_internet', 'is_multimedia', 'state', 'need_period', 'stop'));
        foreach ($items as $item) {
            if (empty($item)) continue;
            $arr = array(
                $db->quote($ids[$item->sectionID]),
                $db->quote($item->application),
                $db->quote($item->unit),
                $db->quote($item->unit_2),
                ($item->title_ru != null) ? $db->quote($item->title_ru) : 'NULL',
                ($item->title_en != null) ? $db->quote($item->title_en) : 'NULL',
                $db->quote($item->price_rub),
                $db->quote($item->price_usd),
                $db->quote($item->price_eur),
                $db->quote($item->column_1),
                $db->quote($item->column_2),
                $db->quote($item->column_3),
                $db->quote($item->is_factor),
                $db->quote($item->is_markup),
                $db->quote($item->is_cleaning),
                $db->quote($item->is_sq),
                $db->quote($item->is_water),
                $db->quote($item->badge),
                $db->quote($item->in_stat),
                $db->quote($item->is_electric),
                $db->quote($item->is_internet),
                $db->quote($item->is_multimedia),
                $db->quote($item->state),
                $db->quote($item->need_period),
                $db->quote($item->stop),
            );
            $values = implode(', ', $arr);
            $query->values($values);
        }
        $db->setQuery($query)->execute();
        return true;
    }

    /**
     * Возвращает список секций указанного прайс-листа.
     * @param   int $sectionID ID секции прайс-листа.
     * @return  array    Массив с объектами.
     * @since 1.1.9
     */
    private function getPriceSections(int $sectionID): array
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__prc_sections')
            ->where("`priceID` = {$sectionID}");
        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Возвращает список пунктов указанного прайс-листа.
     * @param array $ids Массив с ID прайс-листов.
     * @return array    Массив с объектами.
     * @since 1.1.9
     */
    private function getPriceItems(array $ids): array
    {
        $ids = implode(', ', $ids);
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from('#__prc_items')
            ->where("`sectionID` IN ({$ids})");
        return $db->setQuery($query)->loadObjectList();
    }

}