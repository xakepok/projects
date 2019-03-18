<?php
use Joomla\CMS\MVC\Controller\AdminController;
defined('_JEXEC') or die;

class ProjectsControllerSearch extends AdminController
{
    public function getModel($name = 'Contract', $prefix = 'ProjectsModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function asset()
    {
        $projectID = 9;
        $old_id = JFactory::getApplication()->input->getInt('old_id', 0);
        $new_id = JFactory::getApplication()->input->getInt('new_id', 0);
        $cm = $this->getModel();
        $contract = $cm->getItem(array('expID' => $new_id, 'prjID' => $projectID));
        $is_contract = ($contract->id != null ? true : false);
        $msg = array();
        if (!$is_contract) {
            $arr = array();
            $arr['id'] = null;
            $arr['expID'] = $new_id;
            $arr['prjID'] = $projectID;
            $arr['status'] = 1;
            $cm->save($arr);
            $msg[] = JText::sprintf('COM_PROJECTS_CONTRACT_CREATED');
        }
        else {
            $msg[] = JText::sprintf('COM_PROJECTS_CONTRACT_EXISTS');
        }
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->delete("`#__tmp_army`")
            ->where("`id` = {$old_id}");
        $db->setQuery($query)->execute();
        $msg[] = JText::sprintf('COM_PROJECTS_TMP_EXHIBITOR_DELETED');
        $this->setRedirect('index.php?option=com_projects&view=search');
        $this->setMessage(implode(". ", $msg));
        $this->redirect();
        jexit();
    }

    //Одноразовый метод для восстановления сделок
    public function repair()
    {
        $db =& JFactory::getDbo();
        $query = "SELECT * FROM `#__prj_user_action_log` WHERE `itemID` IN (SELECT `id` FROM `#__prj_contracts` WHERE `prjID` = 9) ORDER BY `id`";
        $items = $db->setQuery($query)->loadObjectList();
        $result = array();
        $upd = array();
        foreach ($items as $item) {
            $arr = json_decode($item->params, true);
            $query = "UPDATE `#__prj_contracts` SET ";
            foreach ($arr as $param => $value) {
                if ($param == "id" || $param == "tags" || $param == "rubrics") continue;
                $upd[] = $db->qn($param) . " = " . $db->q($value);
            }
            $query .= implode(", ", $upd);
            $query .= " WHERE `id` = {$item->itemID} LIMIT 1;";
            $db->setQuery($query)->execute();
            $result[] = $query;
        }
        exit(var_dump($result));
    }
}
