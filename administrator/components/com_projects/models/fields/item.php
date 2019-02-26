<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');
use Joomla\CMS\MVC\Model\AdminModel;

class JFormFieldItem extends JFormFieldGroupedList
{
    protected $type = 'Item';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $view = JFactory::getApplication()->input->getString('view');

        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`i`.`id`, `i`.`title_ru`, `p`.`title` as `price`, `s`.`title` as `section`")
            ->from('`#__prc_items` as `i`')
            ->leftJoin("`#__prc_sections` as `s` ON `s`.`id` = `i`.`sectionID`")
            ->leftJoin("`#__prc_prices` as `p` ON `p`.`id` = `s`.`priceID`")
            ->order("`p`.`title`, `i`.`title_ru`");

        if ($view == 'stat') {
            $session = JFactory::getSession();
            if ($session->get('projectID')) {
                $projectID = $session->get('projectID');
                $priceID = ProjectsHelper::getProjectPrice($projectID);
                $query->where("`s`.`priceID` = {$priceID}");
                $session->clear('projectID');
            }
        }
        if ($view == 'catalogs' || $view == 'catalog' || $view == 'stand')
        {
            $session = JFactory::getSession();
            $id = JFactory::getApplication()->input->getInt('id', null);
            if ($id != null) {
                $sm = AdminModel::getInstance('Stand', 'ProjectsModel');
                $cid = $sm->getItem($id)->contractID ?? null;
            }
            $contractID = $session->get('contractID') ?? $cid;
            $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
            if ($view == 'stand') {
                $tip = ProjectsHelper::getContractType($contractID);
                if ($tip == 0) $query->where("`i`.`is_sq` = 1");
            }
            if ($view == 'stand') {
                if ($contractID != null) {
                    $projectID = ProjectsHelper::getContractProject($contractID);
                    $tip = ProjectsHelper::getProjectType($projectID);
                    $priceID = ProjectsHelper::getProjectPrice($projectID);
                    $query->where("`s`.`priceID` = {$priceID}");
                    $contract = $cm->getItem($contractID);
                    $coExps = ProjectsHelper::getContractCoExp($contract->expID, $contract->prjID);
                    if (count($coExps) == 0) {
                        //$session->clear('contractID');
                    }
                }
            }
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            if ($view != 'stand') {
                if (!isset($options[$item->price])) $options[$item->price] = array();
                $options[$item->price][] = JHtml::_('select.option', $item->id, $item->title_ru);
            }
            else {
                if ($tip == 1) {
                    if (!isset($options[$item->section])) $options[$item->section] = array();
                    $options[$item->section][] = JHtml::_('select.option', $item->id, $item->title_ru);
                }
                else {
                    if (!isset($options[$item->price])) $options[$item->price] = array();
                    $options[$item->price][] = JHtml::_('select.option', $item->id, $item->title_ru);
                }
            }
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getGroups(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getGroups();
    }
}