<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldScore extends JFormFieldList
{
    protected $type = 'Score';

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, DATE_FORMAT(`s`.`dat`,'%d.%m.%Y') as `dat`, `c`.`number`")
            ->from("`#__prj_scores` as `s`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->where("`c`.`status` = 1")
            ->order("`c`.`number` DESC");
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'payment') && $session->get('scoreID') != null)
        {
            $scoreID = $session->get('scoreID');
            $query->where("`s`.`id` = {$scoreID}");
            $session->clear('scoreID');
        }
        if ($view == 'payment')
        {
            $query->where("`s`.`state` = 0");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = JText::sprintf('COM_PROJECTS_HEAD_SCORE_NUM_FROM', $item->id, $item->dat, $item->number);
            $options[] = JHtml::_('select.option', $item->id, $title);
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}