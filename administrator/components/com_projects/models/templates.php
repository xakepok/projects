<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelTemplates extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'manager',
                'tip', 't.tip',
                't.title',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`t`.`id`, `t`.`tip`, `t`.`title`, IFNULL(IF(LENGTH(`t`.`text`)>47,CONCAT(LEFT(`t`.`text`,50),'...'),`t`.`text`),'') AS `text`")
            ->select("`u`.`name` as `manager`")
            ->from("`#__prj_templates` as `t`")
            ->leftJoin("`#__users` as `u` ON `u`.`id` = `t`.`managerID`");
        if (!ProjectsHelper::canDo('core.general'))
        {
            $userID = JFactory::getUser()->id;
            $query->where("`t`.`managerID` = {$userID}");
        }
        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $search = $db->quote('%' . $db->escape($search, true) . '%', false);
            $query->where('(`t`.`title` LIKE ' . $search . 'OR `t`.`text` LIKE ' . $search . ')');
        }
        // Фильтруем по менеджеру.
        $manager = $this->getState('filter.manager');
        if (is_numeric($manager)) {
            $query->where('`t`.`managerID` = ' . (int)$manager);
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 't.title');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        $return = base64_encode("index.php?option=com_projects&view=templates");
        foreach ($items as $item)
        {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['manager'] = $item->manager;
            $arr['title'] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=template.edit&amp;id={$item->id}"), $item->title);
            $arr['text'] = $item->text;
            $arr['tip'] = ProjectsHelper::getTemplateType($item->tip);
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $tip = $this->getUserStateFromRequest($this->context . '.filter.tip', 'filter_tip');
        $this->setState('filter.tip', $tip);
        $manager = $this->getUserStateFromRequest($this->context . '.filter.manager', 'filter_manager');
        $this->setState('filter.manager', $manager);
        parent::populateState('t.title', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.manager');
        $id .= ':' . $this->getState('filter.tip');
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}