<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldSection extends JFormFieldGroupedList
{
    protected $type = 'Section';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $view = JFactory::getApplication()->input->getString('view');

        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`title` as `section`, `p`.`title` as `price`")
            ->from('`#__prc_sections` as `s`')
            ->leftJoin('`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`')
            ->order("`s`.`priceID`");

        if ($view == 'item') {
            $session = JFactory::getSession();
            if ($session->get('projectID')) {
                $projectID = $session->get('projectID');
                $priceID = ProjectsHelper::getProjectPrice($projectID);
                $query->where("`s`.`priceID` = {$priceID}");
                $session->clear('projectID');
            }
            else {
                $project = ProjectsHelper::getActiveProject();
                if (is_numeric($project)) {
                    $price = ProjectsHelper::getProjectPrice($project);
                    if ($price != null) $query->where('`p`.`id` = ' . (int) $price);
                }
            }
        }
        else {
            $project = ProjectsHelper::getActiveProject();
            if (is_numeric($project)) {
                $price = ProjectsHelper::getProjectPrice($project);
                if ($price != null) $query->where('`p`.`id` = ' . (int) $price);
            }
        }

        $result = $db->setQuery($query)->loadObjectList();
        $options = array();

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->price])) {
                    $options[$p->price] = array();
                }
                $name = $p->section;
                $options[$p->price][] = JHtml::_('select.option', $p->id, $name);
            }
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getGroups(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getGroups();
    }
}