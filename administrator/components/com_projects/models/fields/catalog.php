<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCatalog extends JFormFieldList
{
    protected $type = 'Catalog';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `number`, `square`")
            ->from("`#__prj_catalog`")
            ->order("`number`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = sprintf("№%s (%s %s)", $item->number, $item->square, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM'));
            $arr = array('data-square' => $item->square, 'data-num' => $item->number);
            $params = array('attr' => $arr, 'option.attr' => 'optionattr');
            $options[] = JHtml::_('select.option', $item->id, $title, $params);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}