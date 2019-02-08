<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
use Joomla\CMS\MVC\Model\AdminModel;

class JFormFieldDelegate extends JFormFieldList
{
    protected $type = 'Delegate';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, `c`.`number`, DATE_FORMAT(`c`.`dat`,'%d.%m.%Y') as `dat`, `c`.`status`")
            ->select("IFNULL(`p`.`title_ru`,`p`.`title_en`) as `project`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->from('`#__prj_contracts` as `c`')
            ->leftJoin("`#__prj_projects` as `p` ON `p`.`id` = `c`.`prjID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->order("`c`.`id`");
        $session = JFactory::getSession();
        if ($session->get('contractID') != null)
        {
            $contractID = $session->get('contractID');
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            $contract = $cm->getItem($contractID);
            $coExps = implode(', ', ProjectsHelper::getContractCoExp($contract->expID, $contract->prjID));
            $query->where("`c`.`id` IN ({$coExps})");
            $session->clear('contractID');
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