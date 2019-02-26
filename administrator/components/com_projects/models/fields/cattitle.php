<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCattitle extends JFormFieldList
{
    protected $type = 'Cattitle';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $id = JFactory::getApplication()->input->getInt('id', 0);

        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from("`#__prj_catalog_titles`")
            ->order("`title`");
        // Фильтруем по проекту.
        $session = JFactory::getSession();
        if ($view != 'project') {
            if ($view == 'catalog' && $session->get('projectID') != null) {
                $projectID = $session->get('projectID');
                $titleID = ProjectsHelper::getProjectCatalog($projectID);
                $query->where("`id` = {$titleID}");
                $session->clear('projectID');
            }
            else {
                if ($id == 0) {
                    $project = ProjectsHelper::getActiveProject();
                    if (is_numeric($project)) {
                        $catalog = ProjectsHelper::getProjectCatalog($project);
                        if ($catalog != null) $query->where('`id` = ' . (int)$catalog);
                    }
                }
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