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
            ->select("`cat`.`id` as `standID`, `cat`.`number`, `cat`.`square`")
            ->from("`#__prj_catalog` as `cat`")
            ->order("`cat`.`number`");
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'stand') && $session->get('contractID') != null)
        {
            $contractID = $session->get('contractID');
            $projectID = ProjectsHelper::getContractProject($contractID);
            $catalogID = ProjectsHelper::getProjectCatalog($projectID);
            $query->where("`cat`.`titleID` = {$catalogID}");
            $query->where("`cat`.`id` NOT IN (SELECT `c`.`catalogID` FROM `#__prj_contract_stands` as `c` LEFT JOIN `#__prj_contracts` AS `con` ON `con`.`id` = `c`.`contractID` WHERE `con`.`prjID` = {$projectID} AND `con`.`status` != 0)");
            $session->clear('contractID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = sprintf("№%s (%s %s)", $item->number, $item->square, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM'));
            $arr = array('data-square' => $item->square, 'data-num' => $item->number);
            $params = array('attr' => $arr, 'option.attr' => 'optionattr');
            $options[] = JHtml::_('select.option', $item->standID, $title, $params);
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