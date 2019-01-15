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
            ->select("`id` as `standID`, `number`, `square`")
            ->from("`#__prj_catalog`")
            ->order("`number`");
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'stand') && $session->get('contractID') != null)
        {
            $contractID = $session->get('contractID');
            $query->select("(SELECT IFNULL(SUM(`sq`),0) FROM `#__prj_contract_stands` WHERE `id` = `standID`) as `busy`");
            $projectID = ProjectsHelper::getContractProject($contractID);
            $catalogID = ProjectsHelper::getProjectCatalog($projectID);
            $query->where("`titleID` = {$catalogID}");
            $query->select("(SELECT `square`-`busy`) as `free`");;
            $session->clear('contractID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            if ($item->free == 0) continue;
            $title = sprintf("№%s (%s %s)", $item->number, $item->free, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM'));
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