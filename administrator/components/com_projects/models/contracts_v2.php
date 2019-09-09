<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelContracts_v2 extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'num', 'dat', 'project', 'exhibitor', 'todos', 'manager', 'status', 'status_weight', 'doc_status', 'amount', 'payment', 'debt',
                'sort_amount, debt', 'sort_amount, payments', 'sort_amount, amount', 'parent',
            );
        }

        $this->task = JFactory::getApplication()->input->getString('task', 'display');
        $this->return = ProjectsHelper::getReturnUrl();

        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_contracts_v2`");

        $input = JFactory::getApplication()->input;

        //Поиск по ID проекта (из URL)
        $projectID = $input->getString('projectID', '');
        if (is_numeric(($projectID))) {
            $projectID = (int) $projectID;
            $query->where("`projectID` = {$projectID}");
        }
        else {
            // Фильтруем по проекту.
            $project = ProjectsHelper::getActiveProject();
            if (is_numeric($project)) {
                $project = (int)$project;
                $query->where("`projectID` = {$project}");
            }
        }

        //Поиск по ID экспонента (из URL)
        $exhibitorID = $input->getString('exhibitorID', '');
        if (is_numeric(($exhibitorID))) {
            $exhibitorID = (int) $exhibitorID;
            $query->where("`exhibitorID` = {$exhibitorID}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 'plan_dat');
        $orderDirn = $this->state->get('list.direction', 'asc');
        if ($orderCol == 'num') {
            if ($orderDirn == 'ASC') $orderCol = 'LENGTH(num), num';
            if ($orderDirn == 'DESC') $orderCol = 'LENGTH(num) desc, num';
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array());

        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['num'] = $item->num;
            $arr['dat'] = ($item->dat != null) ? JDate::getInstance($item->dat)->format("d.m.Y") : '';
            $arr['currency'] = $item->currency;
            $arr['project'] = $item->project;
            $arr['projectID'] = $item->projectID;
            $arr['exhibitor'] = $item->exhibitor;
            $arr['exhibitorID'] = $item->exhibitorID;
            $arr['isCoExp'] = $item->isCoExp;
            $arr['parentID'] = $item->parentID;
            $arr['parent'] = $item->parent;
            $arr['todos'] = $item->todos;
            $arr['manager'] = $item->manager;
            $arr['status'] = JText::sprintf($item->status);
            $arr['status_code'] = $item->status_code;
            $arr['doc_status'] = JText::sprintf(($item->doc_status == '1') ? 'JYES' : 'JNO');
            $arr['amount'] = (float) $item->amount;
            $arr['payments'] = (float) $item->payments;
            $arr['debt'] = (float) $item->debt;
            $arr['payerID'] = (float) $item->payerID;
            $arr['payer'] = (float) $item->payer;
            $result['items'][] = $this->prepare($arr);
        }

        return $result;
    }

    private function prepare(array $arr): array
    {
        if ($this->task != 'xls') {
            //Edit link
            $id = $arr['id'];
            $text = JText::sprintf('COM_PROJECTS_ACTION_GO');
            $params = array('title' => "ID: {$id}", "style" => "font-size: 0.9em");
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$id}&amp;return={$this->return}");
            $arr['edit'] = JHtml::link($url, $text, $params);
            //Project link
            if (ProjectsHelper::canDo('projects.access.projects')) {
                $id = $arr['projectID'];
                $text = $arr['project'];
                $params = array('title' => "ID: {$id}", "style" => "font-size: 0.9em");
                $url = JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$id}&amp;return={$this->return}");
                $arr['project'] = JHtml::link($url, $text, $params);
            }
            //Exhibitor link
            $id = $arr['exhibitorID'];
            $text = $arr['exhibitor'];
            $params = array('title' => "ID: {$id}", "style" => "font-size: 0.9em");
            $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$id}&amp;return={$this->return}");
            $arr['exhibitor'] = JHtml::link($url, $text, $params);
            //Todos link
            $id = $arr['id'];
            $text = $arr['todos'];
            $params = array("style" => "font-size: 0.9em");
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$id}");
            $arr['todos'] = JHtml::link($url, $text, $params);
            //Stands
            $arr['stands'] = implode(", ", $this->getStandsForContract($arr['id']));
            //CoExp
            if ($arr['isCoExp'] == 1 && !empty($arr['parent'])) {
                $projectID = $arr['projectID'];
                $parentID = $arr['parentID'];
                $text = $arr['parent'];
                $url = JRoute::_("index.php?option=com_projects&amp;view=contracts_v2&amp;exhibitorID={$parentID}&amp;projectID={$projectID}");
                $arr['isCoExp'] = JHtml::link($url, $text, JText::sprintf('COM_PROJECTS_GO_FIND_PARENT'));
            }
            else {
                $arr['isCoExp'] = '';
            }
            //Currencies
            $arr['amount'] = ProjectsHelper::getCurrency((float) $arr['amount'], $arr['currency']);
            $arr['payments'] = ProjectsHelper::getCurrency((float) $arr['payments'], $arr['currency']);
            //Debt
            $debt = (float) $arr['debt'];
            $color = ""; //Цвет текста с долгом
            if ($debt == 0) $color = 'green';
            elseif ($debt < 0) $color = 'red';
            $text = ProjectsHelper::getCurrency((float) $arr['debt'], $arr['currency']);
            $arr['debt'] = "<span style='color: {$color}'>{$text}</span>";
            //For Accountants
            if (ProjectsHelper::canDo('projects.access.finanses.full')) {
                if ($debt > 0 && ($arr['status_code'] == '1' || $arr['status_code'] == '10')) {
                    $url = JRoute::_("index.php?option=com_projects&amp;task=score.add&amp;contractID={$arr['id']}&amp;return={$this->return}");
                    $arr['debt'] = JHtml::link($url, $text, array("style" => "color: {$color}"));
                }
            }
        }
        return $arr;
    }

    /**
     * Возвращает стенды сделки
     * @param int $contractID ID сделки
     * @return array
     * @since 1.0.8.6
     */
    public function getStandsForContract(int $contractID): array
    {
        $stands = ProjectsHelper::getContractStands($contractID);
        $result = array();
        $tip = ProjectsHelper::getContractType($contractID);
        foreach ($stands as $stand) {
            $url = JRoute::_("index.php?option=com_projects&amp;task=stand.edit&amp;id={$stand->id}&amp;contractID={$stand->contractID}&amp;return={$this->return}");
            $result[] = ($contractID != $stand->contractID && $tip == 0) ? $stand->number : JHtml::link($url, ($tip == 0) ? $stand->number : $stand->title);
        }
        return $result;
    }

    private $task, $return;
}
