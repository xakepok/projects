<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldChildren extends JFormFieldList
{
    protected $type = 'Children';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $contractID = JFactory::getApplication()->input->getInt('id', 0);
        if ($contractID == 0) return array();
        $projectID = ProjectsHelper::getContractProject($contractID);
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("DISTINCT `e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`")
            ->select("`r`.`name` as `region`")
            ->from('`#__prj_exp` as `e`')
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`")
            ->rightJoin("`#__prj_contracts` as `c` ON `c`.`expID` = `e`.`id`")
            ->where("`c`.`prjID` = {$projectID}");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $name = sprintf("%s (%s)", $title, $item->region);
            $options[] = JHtml::_('select.option', $item->id, $name);
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