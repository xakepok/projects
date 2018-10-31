<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldProject extends JFormFieldList
{
    protected $type = 'Project';

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prj_projects')
            ->order("`title`");
        if ($view == 'contract')
        {
            $query->where("`priceID` IS NOT NULL");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}