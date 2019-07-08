<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class ProjectsModelPersons extends ListModel
{
    public function __construct($config = array())
    {
        $this->exhibitorID = 257;
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("*")
            ->from("`#__prj_exp_persons`")
            ->where("`exbID` = {$this->exhibitorID}");
        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $url = JRoute::_("index.php?view=person&amp;id={$item->id}");
            $params = array('class' => 'card-link');
            $arr['edit'] = JHtml::link($url, JText::sprintf('JACTION_EDIT'), $params);
            $arr['fio'] = $item->fio;
            $arr['post'] = $item->post;
            $arr['contacts'] = array();
            if ($item->phone_work !== null) {
                $arr['contacts'][JText::sprintf('COM_PROJECTS_HEAD_PERSON_PHONE_WORK')] = JHtml::link("tel:{$item->phone_work}", $item->phone_work);
            }
            if ($item->phone_mobile !== null) {
                $arr['contacts'][JText::sprintf('COM_PROJECTS_HEAD_PERSON_PHONE_MOBILE')] = JHtml::link("tel:{$item->phone_mobile}", $item->phone_mobile);
            }
            if ($item->email !== null) {
                $arr['contacts']['Email'] = JHtml::link("mailto:{$item->email}", $item->email);
            }
            $arr['comment'] = $item->comment;
            if (!empty($item->fio)) $result[] = $arr;
        }
        return $result;
    }

    public function getTmpl(): int
    {
        $items = $this->getItems();
        $result = 0;
        if (count($items) == 0) return $result;
        for ($i = 5; $i >= 1; $i--) {
            if (count($items) % $i === 0) return $i;
        }
        return $result;
    }

    private $exhibitorID;
}