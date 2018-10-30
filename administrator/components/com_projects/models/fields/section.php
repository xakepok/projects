<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldSection extends JFormFieldGroupedList
{
    protected $type = 'Section';

    protected function getGroups()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`title` as `section`, `p`.`title` as `price`")
            ->from('`#__prc_sections` as `s`')
            ->leftJoin('`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`')
            ->order("`s`.`priceID`");
        $result = $db->setQuery($query)->loadObjectList();
        $options = array();

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->price])) {
                    $options[$p->price] = array();
                }
                $name = $p->section;
                $options[$p->price][] = JHtml::_('select.option', $p->id, $name);
            }
        }

        $options = array_merge(parent::getGroups(), $options);

        return $options;
    }
}