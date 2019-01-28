<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelHistory extends AdminModel {
    public function getTable($name = 'History', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    /**
     * Возвращает последний статус контракта
     * @param int $contractID ID контракта
     * @return int
     * @since 1.2.6
     */
    public function getLastStatus(int $contractID): int
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("IFNULL(`status`,-1) as `status`")
            ->from("`#__prj_exp_history`")
            ->where("`contractID` = {$contractID}")
            ->order("`dat` DESC");
        $status = $db->setQuery($query, 0, 1)->loadResult();
        return $status ?? -1;
    }

    /**
     * Возвращает историю работы с экспонентом в сделках
     * @param int $exhibitorID ID экспонента
     * @return array
     * @since 1.0.8.1
     */
    public function getHistory(int $exhibitorID): array
    {
        $result = array('process' => array(), 'complete' => array());
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id` as `contractID`, `c`.`number`, `c`.`dat`, `c`.`status`")
            ->select("`p`.`id` as `projectID`, `p`.`title` as `project`")
            ->select("`u`.`name` as `manager`")
            ->select("IF(`p`.`date_end`>CURRENT_TIMESTAMP(),'1','0') as `is_active`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`")
            ->where("`c`.`expID` = {$exhibitorID}");
        $items = $db->setQuery($query)->loadObjectList();
        $return = base64_encode("index.php?option=com_projects&task=exhibitor.edit&id={$exhibitorID}");
        foreach ($items as $item) {
            $arr = array();
            $url = JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$item->projectID}&amp;return={$return}");
            $arr['project'] = (ProjectsHelper::canDo('core.general')) ? JHtml::link($url, $item->project) : $item->project;
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$item->contractID}");
            $arr['todos'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_BLANK_TODOS'));
            $arr['manager'] = $item->manager;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            if ($item->status == '1' && !empty($item->number)) $arr['status'] .= " №{$item->number}";
            if ($item->status == '1' && empty($item->number)) $arr['status'] = JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
            $url_contract = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
            $arr['status'] = JHtml::link($url_contract, $arr['status']);
            $result[(!$item->is_active) ? 'complete' : 'process'][] = $arr;
        }
        return $result;
    }

    /**
     * Возвращает историю работы с экспонентом в сделках
     * @param int $expID ID экспонента
     * @return array
     * @since 1.2.6
     * @deprecated
     * Не используется более с версии 1.0.8.1
     */
    public function getExpHistory(int $expID): array
    {
        $result = array('process' => array(), 'complete' => array());
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("DATE_FORMAT(`h`.`dat`,'%d.%m.%Y %k:%i') as `dat`, `h`.`status`")
            ->select("`h`.`contractID`")
            ->select("`c`.`number`")
            ->select("`u`.`name` as `manager`")
            ->select("`p`.`id` as `projectID`, `p`.`title` as `project`, IF(`p`.`date_end`>CURRENT_TIMESTAMP(),'process','complete') as `section`")
            ->from("`#__prj_exp_history` as `h`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `h`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `h`.`managerID`")
            ->order('`h`.`dat` DESC')
            ->where("`c`.`expID` = {$expID}");

        $items = $db->setQuery($query)->loadObjectList();
        $return = base64_encode("index.php?option=com_projects&task=exhibitor.edit&id={$expID}");
        foreach ($items as $item) {
            $arr = array();
            $arr['dat'] = $item->dat;
            $url = JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$item->projectID}&amp;return={$return}");
            $arr['project'] = (ProjectsHelper::canDo('core.general')) ? JHtml::link($url, $item->project) : $item->project;
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$item->contractID}");
            $arr['todos'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_BLANK_TODOS'));
            $arr['projectID'] = $item->projectID;
            $arr['manager'] = $item->manager;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            if ($item->status == '1' && !empty($item->number)) $arr['status'] .= " №{$item->number}";
            if ($item->status == '1' && empty($item->number)) $arr['status'] = JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
            $url_contract = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
            $arr['status'] = JHtml::link($url_contract, $arr['status']);
            $section = $item->section;
            if ($item->status == '0' || $item->status == '1')
            {
                $result[$section][$item->projectID] = $arr;
            }
            else
            {
                if (!isset($result[$section][$item->projectID]))
                {
                    $result[$section][$item->projectID] = $arr;
                }
                else
                {
                    if ($item->status > $result[$section][$item->projectID]['status']) $result[$section][$item->projectID] = $arr;
                }
            }
        }
        //$result = $this->checkDuplicate($result);
        return $result;
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function getForm($data = array(), $loadData = true)
    {

    }

    protected function loadFormData()
    {

    }

    protected function prepareTable($table)
    {
    	$nulls = array(); //Поля, которые NULL

	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    public function publish(&$pks, $value = 1)
    {
        return parent::publish($pks, $value);
    }

    /**
     * Удаляет дубликаты из истории участия экспонента в проектах, оставляя только последний статус
     * @param array $history
     * @return array
     * @since 1.0.2.0
     */
    private function checkDuplicate(array $history): array
    {
        foreach ($history['complete'] as $projectID => $item)
        {
            if (isset($history['process'][$projectID])) unset($history['process'][$projectID]);
        }
        return $history;
    }
}