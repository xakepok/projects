<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelProject extends AdminModel {
    public function getTable($name = 'Projects', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function save($data)
    {
        $dat = new DateTime($data['date_start']);
        $data['date_start'] = $dat->format("Y-m-d");
        $dat = new DateTime($data['date_end']);
        $data['date_end'] = $dat->format("Y-m-d");
        $s = parent::save($data);
        $projectID = $data['id'] ?? $this->getDbo()->insertid();
        $this->saveRubrics($projectID, $data['rubrics'] ?? array());
        return $s;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        if ($item->id == null) {
            $item->managerID = JFactory::getUser()->id;
        }
        if ($item->id != null) {
            $rubrics = ProjectsHelper::getProjectRubrics($item->id);
            if (!empty($rubrics)) $item->rubrics = $rubrics;
        }
        return $item;
    }

    /**
     * Возвращает массив с элементами пункта прайса для текущего проекта
     * @return array
     * @since 1.1.4.5
     */
    public function getPriceItems(): array
    {
        $item = parent::getItem();
        $result = array();
        if ($item->id == null) return $result;
        $items = ProjectsHelper::getProjectPriceItems($item->id);
        $return = base64_encode("index.php?option=com_projects&view=project&layout=edit&id={$item->id}");
        foreach ($items as $punkt) {
            $arr = array();
            $url = JRoute::_("index.php?option=com_projects&amp;task=item.edit&amp;id={$punkt->id}&amp;return={$return}");
            $arr['title'] = JHtml::link($url, $punkt->title_ru);
            $arr['section'] = $punkt->section;
            $arr['unit'] = ProjectsHelper::getUnit($punkt->unit);
            $arr['price'] = sprintf("%s / %s / %s", ProjectsHelper::getCurrency((float) $punkt->price_rub, 'rub'), ProjectsHelper::getCurrency((float) $punkt->price_usd, 'usd'), ProjectsHelper::getCurrency((float) $punkt->price_eur, 'eur'));
            $arr['columns'] = sprintf("%s&#37; / %s&#37; / %s&#37;", $punkt->column_1 * 100 - 100, $punkt->column_2 * 100 - 100, $punkt->column_3 * 100 - 100);
            $url = JRoute::_("index.php?option=com_projects&amp;task=items.removeItem&amp;id={$punkt->id}&amp;return={$return}");
            $arr['delete'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_DELETE'));
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Возврашает массив с элементами каталога продаж для текущего проекта
     * @return array
     * @since 1.1.4.5
     */
    public function getCatalogItems(): array
    {
        $item = parent::getItem();
        $result = array();
        if ($item->id == null) return $result;
        $items = ProjectsHelper::getProjectCatalogItems($item->id);
        $return = base64_encode("index.php?option=com_projects&view=project&layout=edit&id={$item->id}");
        foreach ($items as $catalog) {
            $arr = array();
            $url = JRoute::_("index.php?option=com_projects&amp;task=catalog.edit&amp;id={$catalog->id}&amp;return={$return}");
            $arr['title'] = JHtml::link($url, $catalog->number);
            $arr['square'] = sprintf("%s %s", $catalog->square, JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM'));
            if ($catalog->exhibitorID != null) {
                $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$catalog->exhibitorID}&amp;return={$return}");
                $arr['exhibitor'] = JHtml::link($url, ProjectsHelper::getExpTitle($catalog->title_ru_short, $catalog->title_ru_full, $catalog->title_en));
            }
            else {
                $arr['exhibitor'] = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREE_OK');
            }
            $url = JRoute::_("index.php?option=com_projects&amp;task=catalogs.removeCatalog&amp;id={$catalog->id}&amp;return={$return}");
            $arr['delete'] = JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_DELETE'));
            $result[] = $arr;
        }
        return $result;
    }

    /**
     * Сохраняет привязки рубрик к проекту
     * @param int $projectID ID проекта
     * @param array $rubrics массив с ID рубрик
     * @since 1.1.3.0
     */
    public function saveRubrics(int $projectID, array $rubrics = array()): void
    {
        $pm = AdminModel::getInstance('Prjrubric', 'ProjectsModel');
        $already = ProjectsHelper::getProjectRubrics($projectID);
        if (!empty($rubrics)) {
            foreach ($rubrics as $rubric) {
                $item = $pm->getItem(array('projectID' => $projectID, 'rubricID' => $rubric));
                $arr = array();
                $arr['id'] = $item->id;
                $arr['projectID'] = $projectID;
                $arr['rubricID'] = $rubric;
                if (in_array($rubric, $already)) {
                    if (($key = array_search($rubric, $already)) !== false) unset($already[$key]);
                }
                $pm->save($arr);
            }
        }
        foreach ($already as $rubric) {
            $item = $pm->getItem(array('projectID' => $projectID, 'rubricID' => $rubric));
            if ($item->id != null) $pm->delete($item->id);
        }
    }


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.project', 'project', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.project.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
    	$nulls = array('title_ru', 'title_en', 'priceID', 'contract_prefix'); //Поля, которые NULL
	    foreach ($nulls as $field)
	    {
		    if (!strlen($table->$field)) $table->$field = NULL;
    	}
        parent::prepareTable($table);
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/project.js';
    }
}