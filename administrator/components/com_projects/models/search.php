<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\ListModel;

class ProjectsModelSearch extends ListModel
{
    public function __construct(array $config)
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'title_old',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from("`#__tmp_army`");

        /* Фильтр */
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            $session = JFactory::getSession();
            $params = explode(':', $search);
            $session->set('result', ProjectsHelper::searchExhibitor($params[0], $params[1]));
        }

        /* Сортировка */
        $orderCol  = $this->state->get('list.ordering', 'title_old');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            $arr['id'] = $item->id;
            $arr['title_old'] = $this->getLinks($item->id, $item->title_old);
            $id = JFactory::getApplication()->input->getInt('id', 0);
            $text = JFactory::getApplication()->input->getString('text', '');
            $arr['variants'] = ($id == $item->id && !empty($text)) ? ProjectsHelper::searchExhibitor($item->id, $text) : ProjectsHelper::searchExhibitor($item->id, $item->title_old);
            $session = JFactory::getSession();
            $search = $this->state->get('filter.search');
            $params = explode(':', $search);
            if ($params[0] == $item->id) {
                $arr['variants'] = $session->get('result');
                $session->clear('result');
                $this->state->set('filter.search', '');
            }
            $arr['exhibitorID'] = $item->exhibitorID ?? '';
            $result[] = $arr;
        }
        return $result;
    }

    private function getLinks(int $id, string $name): string
    {
        $arr = explode(' ', $name);
        $result = array();
        foreach ($arr as $item) {
            $item_clean = $item;
            $item = urlencode($item);
            $url = JRoute::_("index.php?option=com_projects&amp;view=search&amp;id={$id}&amp;text={$item}");
            $result[] = JHtml::link($url, $item_clean);
        }
        return implode(" ", $result);
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('title_old', 'asc');
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }
}