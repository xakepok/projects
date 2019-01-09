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
                's.dat',
                'number',
                'number_contract',
                'project',
                'title_ru_short',
                'amount',
                'currency',
                'state',
                'search',
                'exhibitor',
                's.state',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`contractID`, DATE_FORMAT(`s`.`dat`,'%d.%m.%Y') as `dat`, `s`.`number`, `s`.`amount`, `s`.`state`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`, `e`.`id` as `expID`")
            ->select("`c`.`currency`, `c`.`number` as `number_contract`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`, `p`.`id` as `projectID`")
            ->from("`#__prj_scores` as `s`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`");
        if (!ProjectsHelper::canDo('core.accountant') && !ProjectsHelper::canDo('core.general'))
        {
            $userID = JFactory::getUser()->id;
            $query->where("`c`.`managerID` = {$userID}");
        }
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('`e`.`title_ru_full` LIKE ' . $search . 'OR `e`.`title_ru_short` LIKE ' . $search . 'OR `e`.`title_en` LIKE ' . $search);
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
        // Фильтруем по валюте.
        $currency = $this->getState('filter.currency');
        if (!empty($currency))
        {
            $currency = $db->quote('%' . $db->escape($currency, true) . '%', false);
            $query->where("`c`.`currency` LIKE {$currency}");
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

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', '`s`.`id`');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array(), 'amount' => array('rub' => 0, 'usd' => 0, 'eur' => 0), 'debt' => array('rub' => 0, 'usd' => 0, 'eur' => 0));
        $pm = ListModel::getInstance('Payments', 'ProjectsModel');
        $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=scores");
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['contract_id'] = $item->contractID;
            $arr['number'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=score.edit&amp;id={$item->id}"), "№".$item->number);
            $arr['dat'] = $item->dat;
            $number = (!empty($item->number_contract)) ? JText::sprintf('COM_PROJECTS_HEAD_TODO_DOGOVOR_N', $item->number_contract) : JText::sprintf('COM_PROJECTS_TITLE_CONTRACT_WITHOUT_NUMBER');
            $url = JRoute::_("index.php?option=com_projects&amp;task=contract.edit&amp;id={$item->contractID}&amp;return={$return}");
            $arr['contract'] = JHtml::link($url,$number);
            $arr['showPaymens'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=payments&amp;scoreID={$item->id}"), JText::sprintf('COM_PROJECTS_MENU_PAYMENTS'));
            $arr['doPayment'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=payment.add&amp;scoreID={$item->id}"), JText::sprintf('COM_PROJECTS_ACTION_TODO_PAYMENT'));
            $exp = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr['exp'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->expID}&amp;return={$return}"), $exp);
            $arr['project'] = (!ProjectsHelper::canDo('core.general')) ? $item->projectID : JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$item->projectID}&amp;return={$return}"), $item->project);
            $arr['amount'] = sprintf("%s %s", number_format($item->amount, 2, '.', " "), $item->currency);
            $arr['state'] = $item->state;
            $payments = $pm->getScorePayments($item->id);
            $debt = $item->amount - $payments;
            $arr['payments'] = sprintf("%s %s", number_format($payments, 2, '.', " "), $item->currency);
            $arr['debt'] = sprintf("%s %s", number_format($debt, 2, '.', " "), $item->currency);
            $arr['state_text'] = ProjectsHelper::getScoreState($item->state);
            if ($debt > 0 && $debt < $item->amount) $arr['state_text'] = JText::sprintf('COM_PROJECTS_HEAD_SCORE_STATE_2');
            $arr['color'] = ($arr['debt'] < 0) ? 'red' : 'black';
            $result['items'][] = $arr;
            $result['amount'][$item->currency] += $item->amount;
            $result['payments'][$item->currency] += $payments;
            $result['debt'][$item->currency] += $debt;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);
        $contract = $this->getUserStateFromRequest($this->context . '.filter.contract', 'filter_contract', '', 'string');
        $this->setState('filter.contract', $contract);
        $exhibitor = $this->getUserStateFromRequest($this->context . '.filter.exhibitor', 'filter_exhibitor', '', 'string');
        $this->setState('filter.exhibitor', $exhibitor);
        $project = $this->getUserStateFromRequest($this->context . '.filter.project', 'filter_project', '', 'string');
        $this->setState('filter.project', $project);
        $currency = $this->getUserStateFromRequest($this->context . '.filter.currency', 'filter_currency');
        $this->setState('filter.currency', $currency);
        parent::populateState('`s`.`id`', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.contract');
        $id .= ':' . $this->getState('filter.exhibitor');
        $id .= ':' . $this->getState('filter.project');
        $id .= ':' . $this->getState('filter.currency');
        return parent::getStoreId($id);
    }
}