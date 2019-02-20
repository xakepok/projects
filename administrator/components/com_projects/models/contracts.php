<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelContracts extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'c.dat',
                'project',
                'manager',
                'exhibitor',
                'title_ru_short',
                'status',
                'search',
                'plan',
                'plan_dat',
                'currency',
                'number',
                'amount_rub',
                'amount_usd',
                'amount_eur',
                'debt_rub',
                'debt_usd',
                'debt_eur',
                'payments',
                'stand',
                'activity',
                'rubric',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, DATE_FORMAT(`c`.`dat`,'%d.%m.%Y') as `dat`, IFNULL(`c`.`number_free`,`c`.`number`) as `number`, `c`.`status`, `c`.`currency`")
            ->select("`p`.`title_ru` as `project`, `p`.`id` as `projectID`")
            ->select("`e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`id` as `exponentID`")
            ->select("`u`.`name` as `manager`, (SELECT MIN(`dat`) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0) as `plan_dat`")
            ->select("(SELECT COUNT(*) FROM `#__prj_todos` WHERE `contractID`=`c`.`id` AND `state`=0 AND `is_notify` = 0) as `plan`")
            ->select("`a`.`amount_rub`, `a`.`amount_usd`, `a`.`amount_eur`")
            ->select("`pay`.`payments`")
            ->select("IFNULL(`a`.`amount_rub`,0)-IFNULL(`pay`.`payments`,0) as `debt_rub`, IFNULL(`a`.`amount_usd`,0)-IFNULL(`pay`.`payments`,0) as `debt_usd`, IFNULL(`a`.`amount_eur`,0)-IFNULL(`pay`.`payments`,0) as `debt_eur`")
            ->from("`#__prj_contracts` as `c`")
            ->leftJoin("`#__prj_projects` AS `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `expID`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `c`.`managerID`")
            ->leftJoin("`#__prj_contract_amounts` as `a` ON `a`.`contractID` = `c`.`id`")
            ->leftJoin("`#__prj_contract_payments` as `pay` ON `pay`.`contractID` = `c`.`id`");
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('(`e`.`title_ru_full` LIKE ' . $search . ' OR `e`.`title_ru_short` LIKE ' . $search . ' OR `e`.`title_en` LIKE ' . $search . ' OR `p`.`title_ru` LIKE ' . $search . ' OR `e`.`comment` LIKE ' . $search . ')');
        }
        // Фильтруем по видам деятельности.
        $act = $this->getState('filter.activity');
        if (is_numeric($act)) {
            $exponents = ProjectsHelper::getExponentsInActivities($act);
            if (!empty($exponents)) {
                $exponents = implode(', ', $exponents);
                $query->where("`c`.`expID` IN ({$exponents})");
            }
        }
        //Фильтруем по тематикам разделов
        $rubric = $this->getState('filter.rubric');
        if (is_numeric($rubric)) {
            $ids = ProjectsHelper::getRubricContracts($rubric);
            if (!empty($ids)) {
                $ids = implode(', ', $ids);
                $query->where("`c`.`id` IN ($ids)");
            }
            else {
                $query->where("`c`.`id` = 0");
            }
        }

        //Показываем только свои сделки, но если только неактивны фильтры по видам деятельности и тематической рубрике
        if (!ProjectsHelper::canDo('projects.access.contracts.full'))
        {
            if (!is_numeric($act) && !is_numeric($rubric)) {
                $userID = JFactory::getUser()->id;
                $query->where("`c`.`managerID` = {$userID}");
            }
        }

        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (empty($project)) $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $query->where('`c`.`prjID` = ' . (int)$project);
        }

        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor)) {
            $query->where('`c`.`expID` = ' . (int)$exhibitor);
        }
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`c`.`managerID` = ' . (int)$manager);
        }
        // Фильтруем по валюте.
        $currency = $this->getState('filter.currency');
        if (!empty($currency))
        {
            $currency = $db->quote('%' . $db->escape($currency, true) . '%', false);
            $query->where("`c`.`currency` LIKE {$currency}");
        }
        // Фильтруем по статусу.
        $status = $this->getState('filter.status');
        if (is_array($status)) {
            if (!empty($status)) {
                $statuses = implode(', ', $status);
                $query->where("`c`.`status` IN ({$statuses})");
            }
            else
            {
                $query->where("`c`.`status` IS NOT NULL");
            }
        }
        /* Фильтр по ID проекта (только через GET) */
        $id = JFactory::getApplication()->input->getInt('id', 0);
        if ($id != 0)
        {
            $query->where("`c`.`id` = {$id}");
        }
        /* Фильтр по ID экспонента и проекту (только через GET) */
        $exhibitor = JFactory::getApplication()->input->getInt('exhibitorID', 0);
        $project = JFactory::getApplication()->input->getInt('projectID', 0);
        if ($exhibitor != 0 && $project != 0)
        {
            $query->where("`c`.`prjID` = {$project} AND `c`.`expID` = {$exhibitor}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`plan_dat`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array(), 'amount' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'debt' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'payments' => array('rub' => 0, 'usd' => 0, 'eur' => 0));
        $ids = array();
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $return = base64_encode("index.php?option=com_projects&view=contracts");
        $prefixes = ProjectsHelper::getProjectsPrefix();
        foreach ($items as $item) {
            $ids[] = $item->id;
            $arr['id'] = $item->id;
            $arr['dat'] = $item->dat;
            $url = JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}&amp;return={$return}");
            $arr['project'] = ($format != 'html' || ProjectsHelper::canDo('projects.access.projects')) ? $item->project : JHtml::link($url, $item->project);
            $arr['currency'] = $item->currency;
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->id}");
            if ($format == 'html') $arr['edit_link'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_GO'));
            $url = JRoute::_("index.php?option=com_projects&amp;view=todos&amp;contractID={$item->id}");
            $link = JHtml::link($url, $item->plan);
            if ($format == 'html') $arr['todo'] = $link;
            $url = JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->exponentID}&amp;return={$return}");
            $exponentName = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $params = array('class' => 'jutooltip', 'title' => $item->title_ru_full ?? JText::sprintf('COM_PROJECTS_HEAD_EXP_TITLE_RU_FULL_NOT_EXISTS'));
            $exponentUrl = JHtml::link($url, $exponentName, $params);
            $arr['exponent'] = ($format != 'html') ? $exponentName : $exponentUrl;
            $arr['number'] = ($item->number != null) ? $prefixes[$item->projectID]['contract_prefix'].$item->number : '';
            $arr['manager']['title'] = $item->manager ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MANAGER_UNDEFINED');
            $arr['manager']['class'] = (!empty($item->manager)) ? '' : 'no-data';
            $arr['group']['title'] = $item->group ?? JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PROJECT_GROUP_UNDEFINED');
            $arr['group']['class'] = (!empty($item->group)) ? '' : 'no-data';
            if ($format == 'html') $arr['plan'] = $link;
            $arr['status'] = ProjectsHelper::getExpStatus($item->status);
            $arr['stand'] = implode(" ", $this->getStandsForContract($item->id));
            $amount_field = "amount_{$item->currency}";
            $debt_field = "debt_{$item->currency}";
            $amount = $item->$amount_field;
            $payments = $item->payments;
            $debt = $item->$debt_field;
            $arr['amount'] = ($format != 'html') ? $amount : ProjectsHelper::getCurrency((float) $amount, (string) $item->currency);
            $arr['amount_only'] = $amount; //Только цена
            $paid = (float) $amount - (float) $debt;
            $arr['paid'] = ProjectsHelper::getCurrency((float) $paid, (string) $item->currency);
            $arr['debt'] = ($format != 'html') ? $debt : ProjectsHelper::getCurrency((float) $debt, (string) $item->currency);
            $url = JRoute::_("index.php?option=com_projects&amp;task=score.add&amp;contractID={$item->id}&amp;return={$return}");
            $color = ($debt != 0) ? 'red' : 'green';
            $arr['color'] = $color;
            if (ProjectsHelper::canDo('projects.access.finanses.full') && $debt != 0 && $item->status == '1') $arr['debt'] = JHtml::link($url, $arr['debt'], array('title' => JText::sprintf('COM_PROJECTS_ACTION_ADD_SCORE'), 'style' => "color: {$color}"));
            if ($format != 'html') $arr['debt'] = $debt;

            $result['items'][] = $arr;
            if ($item->status != 0) {
                $result['amount'][$item->currency] += $amount;
                $result['debt'][$item->currency] += $debt;
                $result['payments'][$item->currency] += $payments;
            }
        }
        $active_project = ProjectsHelper::getActiveProject();
        if (is_numeric($this->state->get('filter.project')) || $active_project != '')
        {
            $project = $this->state->get('filter.project');
            if (empty($project)) $project = (int) $active_project;
            $statuses = $this->state->get('filter.status') ?? array(1, 2, 3, 4, 5, 6, 7, 8, 9);
            $result['amount']['total'] = ProjectsHelper::getProjectAmount($project, $statuses);
            $result['payments']['total'] = ProjectsHelper::getProjectPayments($project, $statuses);
            $result['debt']['total']['rub'] = $result['amount']['total']['rub'] - $result['payments']['total']['rub'];
            $result['debt']['total']['usd'] = $result['amount']['total']['usd'] - $result['payments']['total']['usd'];
            $result['debt']['total']['eur'] = $result['amount']['total']['eur'] - $result['payments']['total']['eur'];
        }
        return $result;
    }


    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project');
        $this->setState('filter.project', $project);
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor');
        $this->setState('filter.exhibitor', $exhibitor);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $this->setState('filter.manager', $manager);
        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
        $this->setState('filter.status', $status);
        $currency = $this->getUserStateFromRequest($this->context . '.filter.currency', 'filter_currency');
        $this->setState('filter.currency', $currency);
        $activity = $this->getUserStateFromRequest($this->context . '.filter.activity', 'filter_activity', '', 'string');
        $this->setState('filter.state', $activity);
        $rubric = $this->getUserStateFromRequest($this->context . '.filter.rubric', 'filter_rubric', '', 'string');
        $this->setState('filter.rubric', $rubric);
        parent::populateState('`plan_dat`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.status');
        $id .= ':' . $this->getState('filter.currency');
        $id .= ':' . $this->getState('filter.activity');
        $id .= ':' . $this->getState('filter.rubric');
        return parent::getStoreId($id);
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
        $return = base64_encode("index.php?option=com_projects&view=contracts");
        $result = array();
        $tip = ProjectsHelper::getContractType($contractID);
        foreach ($stands as $stand) {
            $url = JRoute::_("index.php?option=com_projects&amp;task=stand.edit&amp;contractID={$stand->contractID}&amp;id={$stand->id}&amp;return={$return}");
            $result[] = ($contractID != $stand->contractID && $tip == 0) ? $stand->number : JHtml::link($url, ($tip == 0) ? $stand->number : $stand->title);
        }
        return $result;
    }

    /**
     * Расчёт стоимости договора
     * @param object $item   объект со сделкой
     * @return float
     * @since 1.2.0
     */
    public function getAmount(object $item): float
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("ROUND(SUM(`i`.`price_{$item->currency}`*`v`.`value`*(CASE WHEN `v`.`columnID`='1' THEN `i`.`column_1` WHEN `v`.`columnID`='2' THEN `i`.`column_2` WHEN `v`.`columnID`='3' THEN `i`.`column_3` END)*IFNULL(`v`.`markup`,1)*IFNULL(`v`.`factor`,1)*IFNULL(`v`.`value2`,1)), 0) as `amount`")
            ->from("`#__prj_contract_items` as `v`")
            ->leftJoin("`#__prc_items` as `i` ON `i`.`id` = `v`.`itemID`")
            ->where("`v`.`contractID` = {$item->id}");
        return (float) 0 + $db->setQuery($query)->loadResult();
    }
}