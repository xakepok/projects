<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldPrice extends JFormFieldList
{
    protected $type = 'Price';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');

        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prc_prices')
            ->order("`title`");

        $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $price = ProjectsHelper::getProjectPrice($project);
            if ($price != null) $query->where('`id` = ' . (int) $price);
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();
        if ($view == 'project') $options[] = JHtml::_('select.option', '', '');

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
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