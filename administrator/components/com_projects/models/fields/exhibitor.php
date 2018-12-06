<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldExhibitor extends JFormFieldList
{
    protected $type = 'Exhibitor';

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`e`.`id`, `e`.`title_ru_full`, `e`.`title_ru_short`, `e`.`title_en`")
            ->select("`r`.`name` as `region`")
            ->from('`#__prj_exp` as `e`')
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");
        $session = JFactory::getSession();
        if (($view == 'person' || $view == 'contract') && $session->get('exbID') != null)
        {
            $exbID = $session->get('exbID');
            $query->where("`e`.`id` = {$exbID}");
            $session->clear('exbID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $title = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $name = sprintf("%s (%s)", $title, $item->region);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}