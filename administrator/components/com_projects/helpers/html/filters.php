<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

abstract class ProjectsHtmlFilters
{
    public static function dat($selected)
    {
        $attribs = array('onChange' => "this.form.submit()", "placeholder" => JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE'));
        return JHtml::calendar($selected, $name='filter_dat', $id='jform_dat', $format = '%Y-%m-%d', $attribs);
    }

    //Фильтр состояний
    public static function state($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'JOPTION_SELECT_PUBLISHED');
        $options = array_merge($options, self::stateOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_state', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр статусов договора
    public static function status($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_STATUS');
        $options = array_merge($options, self::statusOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_status', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр состояний задачи
    public static function stateTodo($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_STATUS_TODO');
        $options = array_merge($options, self::stateTodoOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_state', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр состояний счёта
    public static function stateScore($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_STATUS_SCORE');
        $options = array_merge($options, self::stateScoreOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_state', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр проектов
    public static function project($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_PROJECT');
        $options = array_merge($options, self::projectOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_project', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр неактивных проектов для экспонентов
    public static function projectinactive($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_PROJECT_INACTIVE');
        $options = array_merge($options, self::projectOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_projectinactive', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр видов деятельности
    public static function activity($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_ACTIVITY');
        $options = array_merge($options, self::activityOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_activity', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр прайс-листов
    public static function price($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_PRICE');
        $options = array_merge($options, self::priceOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_price', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр прайс-листов для импорта
    public static function priceImport($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_PRICE_IMPORT');
        $options = array_merge($options, self::priceOptionsImport($selected));

        $attribs = 'class="inputbox" onchange="" id ="valimp"';

        return JHtml::_('select.genericlist', $options, 'filter_price_import', $attribs, 'value', 'text', '', null, true);
    }

    //Фильтр секций прайс-листа
    public static function section($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_SECTION');
        $options = array_merge($options, self::sectionOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_section', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр экспонентов
    public static function exhibitor($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_EXHIBITOR');
        $options = array_merge($options, self::exhibitorOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_exhibitor', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр менеджеров
    public static function manager($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_MANAGER');
        $options = array_merge($options, self::managerOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_manager', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр контрактов
    public static function contract($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_CONTRACT');
        $options = array_merge($options, self::contractOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        return JHtml::_('select.genericlist', $options, 'filter_contract', $attribs, 'value', 'text', $selected, null, true);
    }

    //Фильтр города
    public static function city($selected)
    {
        $options = array();

        $options[][] = JHtml::_('select.option', '', 'COM_PROJECTS_FILTER_SELECT_CITY');
        $options = array_merge($options, self::cityOptions());

        $attribs = 'class="inputbox" onchange="this.form.submit()"';

        //return JHtml::_('select.genericlist', $options, 'filter_city', $attribs, 'value', 'text', $selected, null, true);
        return "<input type='hidden' id='filter_city' name='filter_city' value='' />";
    }

    //Список состояний модели
    public static function stateOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
        $options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
        $options[] = JHtml::_('select.option', '2', 'JARCHIVED');
        $options[] = JHtml::_('select.option', '-2', 'JTRASHED');
        $options[] = JHtml::_('select.option', '*', 'JALL');

        return $options;
    }

    //Список статуса задачи в планировщике
    public static function statusOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', '2', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_2');
        $options[] = JHtml::_('select.option', '3', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_3');
        $options[] = JHtml::_('select.option', '4', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_4');
        $options[] = JHtml::_('select.option', '5', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_5');
        $options[] = JHtml::_('select.option', '6', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_6');
        $options[] = JHtml::_('select.option', '1', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_1');
        $options[] = JHtml::_('select.option', '0', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_0');

        return $options;
    }

    //Список статуса договора
    public static function stateTodoOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', '1', 'COM_PROJECTS_HEAD_TODO_STATE_1');
        $options[] = JHtml::_('select.option', '0', 'COM_PROJECTS_HEAD_TODO_STATE_0');

        return $options;
    }

    //Список статуса оплаты счёта
    public static function stateScoreOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', '1', 'COM_PROJECTS_HEAD_SCORE_STATE_1');
        $options[] = JHtml::_('select.option', '0', 'COM_PROJECTS_HEAD_SCORE_STATE_0');

        return $options;
    }

    public static function projectOptions()
    {
        $groups = implode(', ', JFactory::getUser()->groups);
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title_ru`")
            ->from('#__prj_projects')
            ->where("`groupID` IN ({$groups})")
            ->order("date_start desc");

        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title_ru);
        }

        return $options;
    }

    public static function activityOptions()
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prj_activities')
            ->order("`title`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
    }

    public static function priceOptions()
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prc_prices')
            ->order("`title`");

        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
    }

    public static function priceOptionsImport($selected)
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("DISTINCT `p`.`id`, `p`.`title`")
            ->from('`#__prc_items` as `i`')
            ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `i`.`sectionID`")
            ->leftJoin("`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`")
            ->order("`p`.`title`");
        if (is_numeric($selected))
        {
            $query
                ->where("`s`.`priceID` != {$selected}");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
    }

    public static function sectionOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prc_sections')
            ->order("`title`");
        if ($view == 'items')
        {
            $model = ListModel::getInstance('Items', 'ProjectsModel');
            $price = $model->getState('filter.price');
            if (is_numeric($price))
            {
                $query->where("`priceID` = {$price}");
            }
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
    }

    public static function exhibitorOptions()
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`")
            ->select("`r`.`name` as `region`")
            ->from('`#__prj_exp` as `e`')
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $name = sprintf("%s (%s)", $title, $item->region);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        $options = array_merge($options);

        return $options;
    }

    public static function managerOptions()
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `name`")
            ->from("`#__users`")
            ->order("`name`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        return $options;
    }

    public static function contractOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->from('`#__prj_contracts` as `c`')
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->order("`c`.`id`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $exp = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $name = JText::sprintf('COM_PROJECTS_FILTER_CONTRACT_FIELD', $item->id, $item->project, $exp);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        return $options;
    }

    public static function cityOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, `c`.`name` as `city`, `r`.`name` as `region`, `s`.`name` as `country`")
            ->from('`#__grph_cities` as `c`')
            ->leftJoin('`#__grph_regions` as `r` ON `r`.`id` = `c`.`region_id`')
            ->leftJoin('`#__grph_countries` as `s` ON `s`.`id` = `r`.`country_id`')
            ->order("`c`.`name`")
            ->where("`s`.`state` = 1");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->region])) {
                    $options[$p->region] = array();
                }
                $name = sprintf("%s (%s)", $p->city, $p->country);
                $options[$p->region][] = JHtml::_('select.option', $p->id, $name);
            }
        }

        return $options;
    }
}