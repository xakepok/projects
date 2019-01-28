<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldTemplatetask extends JFormFieldList
{
    protected $type = 'Templatetask';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`, `text`")
            ->from("`#__prj_templates`")
            ->order("`title`")
            ->where("`tip` = 0");
        if (!ProjectsHelper::canDo('core.general'))
        {
            $uid = JFactory::getUser()->id;
            $query->where("`managerID` = {$uid}");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $arr = array('data-text' => $item->text);
            $params = array('attr' => $arr, 'option.attr' => 'optionattr');
            $options[] = JHtml::_('select.option', $item->id, $item->title, $params);
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