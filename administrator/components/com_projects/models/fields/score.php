<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldScore extends JFormFieldList
{
    protected $type = 'Score';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`s`.`id`, `s`.`amount`, DATE_FORMAT(`s`.`dat`,'%d.%m.%Y') as `dat`, `c`.`number`, `c`.`currency`")
            ->select("`e`.`title_ru_short`, `e`.`title_ru_full`, `e`.`title_en`")
            ->select("IFNULL(`e1`.`title_ru_short`,IFNULL(`e1`.`title_ru_full`,IFNULL(`e1`.`title_en`,''))) as `payer`")
            ->from("`#__prj_scores` as `s`")
            ->leftJoin("`#__prj_contracts` as `c` ON `c`.`id` = `s`.`contractID`")
            ->leftJoin("`#__prj_exp` as `e` ON `e`.`id` = `c`.`expID`")
            ->leftJoin("`#__prj_exp` as `e1` ON `e1`.`id` = `c`.`payerID`")
            ->where("`c`.`status` IN (1,10)")
            ->order("`c`.`number` DESC");
        $session = JFactory::getSession();
        $view = JFactory::getApplication()->input->getString('view', '');
        if (($view == 'payment') && $session->get('scoreID') != null)
        {
            $scoreID = $session->get('scoreID');
            $query->where("`s`.`id` = {$scoreID}");
            $session->clear('scoreID');
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $exp = ProjectsHelper::getExpTitle($item->title_ru_short, $item->title_ru_full, $item->title_en);
            if ($item->payer !== '') {
                $exp .= sprintf(" (%s %s)", JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PAYER'), $item->payer);
            }
            $title = JText::sprintf('COM_PROJECTS_HEAD_SCORE_NUM_FROM', $item->id, $item->amount, $item->currency, $item->dat, $item->number, $exp);
            $options[] = JHtml::_('select.option', $item->id, $title);
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