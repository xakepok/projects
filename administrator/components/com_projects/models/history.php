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
     * @param int $expID ID экспонента
     * @return array
     * @since 1.2.6
     */
    public function getExpHistory(int $expID): array
    {
        $result = array('process' => array(), 'complete' => array());
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("DATE_FORMAT(`h`.`dat`,'%d.%m.%Y %k:%i') as `dat`, DATE_FORMAT(`h`.`dat`,'%Y') as `year`, `h`.`status`")
            ->select("`h`.`contractID`")
            ->select("`c`.`number`")
            ->select("`u`.`name` as `manager`")
            ->select("`p`.`id` as `projectID`, `p`.`title` as `project`")
            ->from("`#__prj_exp_history` as `h`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `h`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `h`.`managerID`")
            ->order('`h`.`dat` DESC')
            ->where("`c`.`expID` = {$expID}");

        $items = $db->setQuery($query)->loadObjectList();
        foreach ($items as $item) {
            $arr = array();
            $arr['dat'] = $item->dat;
            $arr['year'] = $item->year;
            $url = JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->contractID}");
            $arr['contract'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_HEAD_TODO_CONTRACT'));
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}");
            $arr['project'] = JHtml::link($url, $item->project);
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;filter_contract={$item->contractID}");
            $arr['todos'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_BLANK_TODOS'));
            $arr['projectID'] = $item->projectID;
            $arr['manager'] = $item->manager;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            if ($item->status == '1') $arr['status'] .= " №{$item->number}";
            $section = ($item->status == '0' || $item->status == '1') ? 'complete' : 'process';
            if ($item->status == '0' || $item->status == '1')
            {
                $result[$section][] = $arr;
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
}