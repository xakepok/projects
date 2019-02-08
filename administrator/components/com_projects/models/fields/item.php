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
            ->select("`i`.`id`, `i`.`title_ru`, `p`.`title` as `price`")
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
            $query->where("`i`.`is_sq` = 1");
            if ($view == 'stand') {
                $session = JFactory::getSession();
                if ($session->get('contractID')) {
                    $contractID = $session->get('contractID');
                    $projectID = ProjectsHelper::getContractProject($contractID);
                    $priceID = ProjectsHelper::getProjectPrice($projectID);
                    $query->where("`s`.`priceID` = {$priceID}");
                    $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
                    $contract = $cm->getItem($contractID);
                    $coExps = ProjectsHelper::getContractCoExp($contract->expID, $contract->prjID);
                    if (count($coExps) == 0) {
                        $session->clear('contractID');
                    }
                }
            }
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            if (!isset($options[$item->price])) $options[$item->price] = array();
            $options[$item->price][] = JHtml::_('select.option', $item->id, $item->title_ru);
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