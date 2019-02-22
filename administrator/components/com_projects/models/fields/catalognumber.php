<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');
//use Joomla\CMS\MVC\Model\AdminModel;

class JFormFieldCatalognumber extends JFormFieldGroupedList
{
    protected $type = 'Catalognumber';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`cat`.`id` as `numberID`, `cat`.`title`")
            ->select("`c`.`title_ru` as `category`, `h`.`title_ru` as `hotel`")
            ->from("`#__prj_catalog` as `cat`")
            ->leftJoin("`#__prj_hotels_number_categories` as `c` ON `c`.`id` = `cat`.`categoryID`")
            ->leftJoin("`#__prj_hotels` as `h` ON `h`.`id` = `c`.`hotelID`")
            ->order("`cat`.`title`");
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'stand') && $session->get('contractID') != null)
        {
            $contractID = $session->get('contractID');
            $projectID = ProjectsHelper::getContractProject($contractID);
            $catalogID = ProjectsHelper::getProjectCatalog($projectID);
            $query->where("`cat`.`titleID` = {$catalogID}");
            /*$id = JFactory::getApplication()->input->getInt('id', 0);
            if ($id != 0) {
                $sm = AdminModel::getInstance('Stand', 'ProjectsModel');
                $stand = $sm->getItem($id);
                $cid = $stand->catalogID;
                $query->where("((`cat`.`id` NOT IN (SELECT `c`.`catalogID` FROM `#__prj_contract_stands` as `c` LEFT JOIN `#__prj_contracts` AS `con` ON `con`.`id` = `c`.`contractID` WHERE `con`.`prjID` = {$projectID} AND `con`.`status` != 0)) OR (`cat`.`id` = {$cid}))");
            }
            else {
                $query->where("`cat`.`id` NOT IN (SELECT `c`.`catalogID` FROM `#__prj_contract_stands` as `c` LEFT JOIN `#__prj_contracts` AS `con` ON `con`.`id` = `c`.`contractID` WHERE `con`.`prjID` = {$projectID} AND `con`.`status` != 0)");
            }*/
            $session->clear('contractID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            if ($item->title == null) continue;
            $title = sprintf("№%s (%s)", $item->title, $item->category);
            if (!isset($options[$item->hotel])) $options[$item->hotel] = array();
            $options[$item->hotel][] = JHtml::_('select.option', $item->numberID, $title);
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