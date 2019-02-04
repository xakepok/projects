<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');

class JFormFieldHotelnumcat extends JFormFieldGroupedList
{
    protected $type = 'Hotelnumcat';
    protected $loadExternally = 0;

    protected function getGroups()
    {
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`c`.`id`, `c`.`title_ru` as `category`, `h`.`title_ru` as `hotel`")
            ->from('`#__prj_hotels_number_categories` as `c`')
            ->leftJoin('`#__prj_hotels` as `h` ON `h`.`id` = `c`.`hotelID`');
        $result = $db->setQuery($query)->loadObjectList();
        $options = array();

        if ($result) {
            foreach ($result as $p) {
                if (!isset($options[$p->hotel])) {
                    $options[$p->hotel] = array();
                }
                $name = $p->category;
                $options[$p->hotel][] = JHtml::_('select.option', $p->id, $name);
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