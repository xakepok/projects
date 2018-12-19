<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelScores extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                's.id',
                's.dat',
                'number',
                'project',
                'title_ru_short', 'exbibitor',
                'search',
                'exhibitor',
                'amount',
                'payments',
                'debt',
                'currency',
                'state', 's.state',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`contractID`, DATE_FORMAT(`s`.`dat`,'%d.%m.%Y') as `dat`, `s`.`number`, `s`.`state`")
            ->select("(SELECT SUM(`amount`) FROM `#__prj_payments` WHERE `scoreID`=`s`.`id`) as `payments`")
            ->select("`s`.`amount`, (SELECT `s`.`amount`-`payments`) as `debt`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`, `e`.`id` as `expID`")
            ->select("`c`.`currency`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`, `p`.`id` as `projectID`")
            ->from("`#__prj_scores` as `s`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`");
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`e`.`title_ru_full` LIKE ' . $search . 'OR `e`.`title_ru_short` LIKE ' . $search . 'OR `e`.`title_en` LIKE ' . $search . 'OR `p`.`title_ru` LIKE ' . $search);
        }
        // Фильтруем по состоянию оплаты.
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('`s`.`state` = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(`s`.`state` = 0 OR `s`.`state` = 1)');
        }
        // Фильтруем по сделке.
        $contract = $this->getState('filter.contract');
        if (is_numeric($contract))
        {
            $query->where('`s`.`contractID` = ' . (int) $contract);
        }
        // Фильтруем по экспоненту.
        $exhibitor = $this->getState('filter.exhibitor');
        if (is_numeric($exhibitor))
        {
            $query->where('`c`.`expID` = ' . (int) $exhibitor);
        }
        // Фильтруем по проекту.
        $project = $this->getState('filter.project');
        if (is_numeric($project))
        {
            $query->where('`c`.`prjID` = ' . (int) $project);
        }
        // Фильтруем по валюте.
        $currency = $this->getState('filter.currency');
        if (!empty($currency))
        {
            $currency = $db->quote('%' . $db->escape($currency, true) . '%', false);
            $query->where("`c`.`currency` LIKE {$currency}");
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`s`.`id`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array(), 'amount' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'debt' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'payments' => array('rub' => 0, 'usd' => 0, 'eur' => 0));
        $pm = ListModel::getInstance('Payments', 'ProjectsModel');
        $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=scores");
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['contract_id'] = $item->contractID;
            $arr['edit'] = (!ProjectsHelper::canDo('core.accountant') && !ProjectsHelper::canDo('core.general')) ? JText::sprintf('COM_PROJECTS_ACTION_EDIT') : JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=score.edit&amp;id={$item->id}"), JText::sprintf('COM_PROJECTS_ACTION_EDIT'));
            $arr['dat'] = $item->dat;
            $arr['number'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id={$item->contractID}&amp;return={$return}"),"№".$item->number);
            $arr['showPaymens'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=payments&amp;filter_score={$item->id}"), JText::sprintf('COM_PROJECTS_MENU_PAYMENTS'));
            $exp = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr['exp'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$item->expID}&amp;return={$return}"), $exp);
            $arr['project'] = (!ProjectsHelper::canDo('core.general')) ? $item->project : JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id={$item->projectID}&amp;return={$return}"), $item->project);
            $arr['amount'] = sprintf("%s %s", number_format($item->amount, 2, '.', " "), $item->currency);
            $arr['state'] = $item->state;
            //$payments = $pm->getScorePayments($item->id);
            $payments = $item->payments;
            $debt = $item->amount - $payments;
            $arr['payments'] = sprintf("%s %s", number_format($payments, 2, '.', " "), $item->currency);
            $arr['doPayment'] = ((!ProjectsHelper::canDo('core.accountant') && !ProjectsHelper::canDo('core.general')) || $debt <= 0) ? JText::sprintf('COM_PROJECTS_ACTION_TODO_PAYMENT') : JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=payment.add&amp;scoreID={$item->id}&amp;return={$return}"), JText::sprintf('COM_PROJECTS_ACTION_TODO_PAYMENT'));
            $arr['debt'] = sprintf("%s %s", number_format($debt, 2, '.', " "), $item->currency);
            $arr['state_text'] = ProjectsHelper::getScoreState($item->state);
            if ($debt > 0 && $debt < $item->amount) $arr['state_text'] = JText::sprintf('COM_PROJECTS_HEAD_SCORE_STATE_2');
            $arr['color'] = ($arr['debt'] < 0) ? 'red' : 'black';
            $result['items'][] = $arr;
            $result['amount'][$item->currency] += $payments;
            $result['debt'][$item->currency] += $debt;
            $result['payments'][$item->currency] += $payments;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string');
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $contract = $this->getUserStateFromRequest($this->context . '.filter.contract', 'filter_contract', '', 'string');
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor', '', 'string');
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project', '', 'string');
        $currency = $this->getUserStateFromRequest($this->context . '.filter.currency', 'filter_currency', '', 'string');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $published);
        $this->setState('filter.contract', $contract);
        $this->setState('filter.exhibitor', $exhibitor);
        $this->setState('filter.project', $project);
        $this->setState('filter.currency', $currency);
        parent::populateState('`s`.`id`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.contract');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.currency');
        return parent::getStoreId($id);
    }
}