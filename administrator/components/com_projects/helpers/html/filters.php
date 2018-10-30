<?php
defined('_JEXEC') or die;

abstract class ProjectsHtmlFilters
{
    //Фильтр состояний
    public static function state($selected)
    {
        $options = array();

        $options[] = JHtml::_('select.option', '', 'JOPTION_SELECT_PUBLISHED');
        $options = array_merge($options, self::stateOptions());

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

    public static function projectOptions()
    {
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prj_projects')
            ->order("`title`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
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
        $db =& JFactory::getDbo();
        $query =& $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__prc_sections')
            ->order("`title`");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item)
        {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
    }
}