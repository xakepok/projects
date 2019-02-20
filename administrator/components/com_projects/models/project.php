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