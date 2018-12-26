<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldItem extends JFormFieldList
{
    protected $type = 'Item';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');

        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`i`.`id`, `i`.`title_ru`")
            ->from('`#__prc_items` as `i`')
            ->order("`i`.`title_ru`");
        $result = $db->setQuery($query)->loadObjectList();

        if ($view == 'stat')
        {
            $query->where("`i`.`in_stat` = 1");
            $session = JFactory::getSession();
            if ($session->get('projectID'))
            {
                $projectID = $session->get('projectID');
                $priceID = ProjectsHelper::getProjectPrice($projectID);
                $query
                    ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `i`.`sectionID`")
                    ->where("`s`.`priceID` = {$priceID}");
                $session->clear('projectID');
            }
        }

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