<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldActivity extends JFormFieldList
{
    protected $type = 'Activity';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prj_activities')
            ->order("`title`");

        $contractors = ProjectsHelper::canDo('projects.access.contractors');
        $general = ProjectsHelper::canDo('core.general');
        $view = JFactory::getApplication()->input->getString('view');
        if ($view == 'exhibitor') {
            if (!$contractors && !$general) {
                $query->where("`for_contractor` = 0");
            }
            if ($contractors && !$general) {
                $query->where("`for_contractor` = 1");
            }
        }

        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

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