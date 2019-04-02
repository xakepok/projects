<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelExhibitors extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                '`id`', '`e`.`id`',
                '`title_ru_short`', '`title_ru_short`',
                '`title_ru_full`', '`title_ru_full`',
                '`title_en`', '`title_en`',
                '`city`', '`city`',
                'projectinactive',
                'projectactive',
                'search',
                'activity',
                'city',
                'status',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('`e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`, `e`.`is_contractor`')
            ->select("`r`.`name` as `city`")
            ->from("`#__prj_exp` as `e`")
            ->leftJoin("`#__prj_exp_bank` as `b` ON `b`.`exbID` = `e`.`id`")
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");

        // Фильтруем по названию (для поиска синонимов)
        $text = JFactory::getApplication()->input->getString('text', '');
        if ($text != '')
        {
            $text = $db->q($text);
            $query->where("(`title_ru_full` LIKE {$text} OR `title_ru_short` LIKE {$text} OR `title_en` LIKE {$text} OR `b`.`inn` LIKE {$text})");
        }
        else
        {
            /* Фильтр */
            $search = $this->getState('filter.search');
            if (!empty($search)) {
                $search = $db->q($search);
                $query->where("(`title_ru_full` LIKE {$search} OR `title_ru_short` LIKE {$search} OR `title_en` LIKE {$search} OR `b`.`inn` LIKE {$search})");
            }
        }
        // Фильтруем по городу.
        $city = $this->getState('filter.city');
        if (is_numeric($city) && $format != 'html') {
            $query->where('`e`.`regID` = ' . (int) $city);
        }
        // Фильтруем по статусу (подрядчик / ндп).
        $status = JFactory::getApplication()->input->getInt('status', null) ?? $this->getState('filter.status');
        if (is_numeric($status)) {
            switch ($status)
            {
                case 0:
                    {
                        $query->where("`e`.`is_contractor` = 0");
                        $query->where("`e`.`is_ndp` = 0");
                        break;
                    }
                case 1:
                    {
                        $query->where("`e`.`is_contractor` = 1");
                        break;
                    }
                case 2:
                    {
                        $query->where("`e`.`is_ndp` = 1");
                        break;
                    }
            }
        }
        // Фильтруем проектам, в которых экспонент не учавствует
        $projectinactive = $this->getState('filter.projectinactive');
        if (is_numeric($projectinactive)) {
            $query->where("`e`.`id` NOT IN (SELECT DISTINCT `expID` FROM `#__prj_contracts` WHERE `prjID` = {$projectinactive})");
        }
        // Фильтруем проектам, в которых экспонент учавствует
        $projectactive = $this->getState('filter.projectactive');
        if (is_numeric($projectactive)) {
            $query->where("`e`.`id` IN (SELECT DISTINCT `expID` FROM `#__prj_contracts` WHERE `prjID` = {$projectactive})");
        }
        //Фильтр по глобальному проекту
        $project = ProjectsHelper::getActiveProject();
        if (is_numeric($project)) {
            $query->where("`e`.`id` IN (SELECT DISTINCT `expID` FROM `#__prj_contracts` WHERE `prjID` = {$project})");
        }
        // Фильтруем по видам деятельности.
        $act = $this->getState('filter.activity');
        if (is_numeric($act)) {
            $exponents = ProjectsHelper::getExponentsInActivities($act);
            if (!empty($exponents)) {
                $exponents = implode(', ', $exponents);
                $query->where("`e`.`id` IN ({$exponents})");
            }
            else
            {
                $query->where("`e`.`id` IN (-1)");
            }
        }
        // Фильтруем по ИНН (для поиска синонимов)
        $inn = JFactory::getApplication()->input->getInt('inn', 0);
        if ($inn !== 0)
        {
            $query->where("`b`.`inn` LIKE {$inn}");
        }

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering', '`title_ru_short`');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $format = JFactory::getApplication()->input->getString('format', 'html');
        $items = parent::getItems();
        $return = base64_encode("index.php?option=com_projects&view=exhibitors");
        $result = array();
        $projectinactive = $this->getState('filter.projectinactive');
        $projectactive = $this->getState('filter.projectactive');
        if (is_numeric($projectinactive))
        {
            $model = AdminModel::getInstance('Project', 'ProjectsModel');
            $project = $model->getItem($projectinactive);
        }
        foreach ($items as $item) {
            $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$item->id}");
            $params = array('class' => 'jutooltip', 'title' => $item->title_ru_full ?? JText::sprintf('COM_PROJECTS_HEAD_EXP_TITLE_RU_FULL_NOT_EXISTS'));
            $link = JHtml::link($url, $title, $params);
            $arr['region'] = $item->city;
            $arr['title'] = ($format != 'html') ? $title : $link;
            if (is_numeric($projectinactive))
            {
                $url = JRoute::_("index.php?option=com_projects&amp;task=contract.add&amp;exhibitorID={$item->id}&amp;projectID={$projectinactive}&amp;return={$return}");
                $arr['contract'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_TITLE_NEW_CONTRACT_WITH_PROJECT', $project->title_ru));
            }
            if (is_numeric($projectactive))
            {
                $url = JRoute::_("index.php?option=com_projects&amp;view=contracts&amp;exhibitorID={$item->id}&amp;projectID={$projectactive}");
                $arr['contracts'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_GO_FIND_CONTRACTS'));
            }
            $result[] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = '`title_ru_short`', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $activity = $this->getUserStateFromRequest($this->context . '.filter.activity', 'filter_activity', '', 'string');
        $this->setState('filter.state', $activity);
        $city = $this->getUserStateFromRequest($this->context . '.filter.city', 'filter_city', '', 'string');
        $this->setState('filter.city', $city);
        $projectinactive = $this->getUserStateFromRequest($this->context . '.filter.projectinactive', 'filter_projectinactive', '', 'string');
        $this->setState('filter.projectinactive', $projectinactive);
        $projectactive = $this->getUserStateFromRequest($this->context . '.filter.projectactive', 'filter_$projectactive', '', 'string');
        $this->setState('filter.projectactive', $projectactive);
        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '', 'string');
        $this->setState('filter.status', $status);
        parent::populateState('`title_ru_short`', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.activity');
        $id .= ':' . $this->getState('filter.city');
        $id .= ':' . $this->getState('filter.projectinactive');
        $id .= ':' . $this->getState('filter.projectactive');
        $id .= ':' . $this->getState('filter.status');
        return parent::getStoreId($id);
    }
}