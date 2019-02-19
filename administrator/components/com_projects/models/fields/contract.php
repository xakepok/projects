<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldContract extends JFormFieldList
{
    protected $type = 'Contract';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, IFNULL(`c`.`number_free`,`c`.`number`) as `number`, DATE_FORMAT(`c`.`dat`,'%d.%m.%Y') as `dat`, `c`.`status`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->from('`#__prj_contracts` as `c`')
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->order("`c`.`id`");
        if (!ProjectsHelper::canDo('core.general') && !ProjectsHelper::canDo('core.accountant'))
        {
            $userID = JFactory::getUser()->id;
            $query->where("`c`.`managerID` = {$userID}");
        }
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'todo' || $view == 'stand' || $view == 'score') && $session->get('contractID') != null)
        {
            $contractID = $session->get('contractID');
            $query->where("`c`.`id` = {$contractID}");
            if ($view != 'stand') $session->clear('contractID');
        }
        if ($view == 'score')
        {
            $query->where("`c`.`status` = 1");
        }
        if ($view == 'contract')
        {
            $query->where("`c`.`isCoExp` = 1");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $exp = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            $name = ProjectsHelper::getContractFieldTitle($item->status ?? 0, $item->number ?? 0, $item->dat ?? '', $exp, $item->project);
            $options[] = JHtml::_('select.option', $item->id, $name);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}