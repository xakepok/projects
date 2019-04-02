<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
use Joomla\CMS\MVC\Model\AdminModel;

class JFormFieldExhibitor extends JFormFieldList
{
    protected $type = 'Exhibitor';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $view = JFactory::getApplication()->input->getString('view');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`e`.`id`")
            ->select('IFNULL(`e`.`title_ru_short`,IFNULL(`e`.`title_ru_full`,`e`.`title_en`)) as `exhibitor`')
            ->select("`r`.`name` as `region`")
            ->from('`#__prj_exp` as `e`')
            ->leftJoin("`#__grph_cities` as `r` ON `r`.`id` = `e`.`regID`");
        $session = JFactory::getSession();
        if (($view == 'person' || $view == 'contract'))
        {
            if ($session->get('exbID') != null) {
                $exbID = $session->get('exbID');
                $query->where("`e`.`id` = {$exbID}");
                $session->clear('exbID');
            }
            if ($view == 'contract' && $id > 0) {
                $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
                $contract = $cm->getItem($id);
                $query->where("`e`.`id` = {$contract->expID}");
            }
        }
        if ($view == 'exhibitor' && $id > 0) {
            $query->where("`e`.`id` != {$id}");
            $children = ProjectsHelper::getExhibitorChildren($id);
            if (!empty($children)) {
                $children = implode(', ', $children);
                $query->where("`e`.`id` NOT IN ({$children})");
            }
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $name = sprintf("%s (%s)", $item->exhibitor, $item->region);
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