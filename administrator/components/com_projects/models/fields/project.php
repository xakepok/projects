<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldProject extends JFormFieldList
{
    protected $type = 'Project';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title_ru`")
            ->from('#__prj_projects')
            ->order("`title`");
        if ($view != 'projects') {
            $groups = implode(', ', JFactory::getUser()->groups);
            $query
                ->where("`groupID` IN ({$groups})")
                ->order("date_start desc");
        }
        if ($view == 'contract')
        {
            $query
                ->where("`priceID` IS NOT NULL")
                ->order("date_start desc");
            $id = JFactory::getApplication()->input->getInt('id', 0);
            if ($id == 0)
            {
                $query->where("date_end >= curdate()");
            }
        }
        $session = JFactory::getSession();
        if ($view == 'contract' && $session->get('projectID') != null)
        {
            $projectID = $session->get('projectID');
            $query->where("`id` = {$projectID}");
            $session->clear('projectID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title_ru);
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