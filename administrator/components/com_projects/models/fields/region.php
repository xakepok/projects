<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldRegion extends JFormFieldGroupedList
{
    protected $type = 'Region';

    protected function getGroups()
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
                $name = sprintf("%s (%s, %s)", $p->city, $p->region, $p->country);
                $options[$p->region][] = JHtml::_('select.option', $p->id, $name);
            }
        }

        $options = array_merge(parent::getGroups(), $options);

        return $options;
    }
}