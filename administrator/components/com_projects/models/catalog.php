<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class ProjectsModelCatalog extends AdminModel {

    public $tip;

    public function __construct(array $config = array())
    {
        $this->id =  JFactory::getApplication()->input->getInt('id', 0);
        parent::__construct($config);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFieldset(): string
    {
        if ($this->id == 0) return 'stand';
        $item = parent::getItem();
        $ctm = AdminModel::getInstance('Cattitle', 'ProjectsModel');
        $ct = $ctm->getItem($item->titleID);
        return ($ct->tip != 0) ? 'number' : 'stand';
    }

    public function getTable($name = 'Catalog', $prefix = 'TableProjects', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function delete(&$pks)
    {
        return parent::delete($pks);
    }

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.catalog', 'catalog', array('control' => 'jform', 'load_data' => $loadData)
        );
        if (empty($form))
        {
            return false;
        }
        if ($this->id == 0)
        {
            $form->removeField('number');
            $form->removeField('square');
            $form->removeField('categoryID');
            $form->removeField('title');
        }
        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.catalog.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
        $this->updateStands($data);
        return parent::save($data);
    }

    /**
     * Изменяет площадь стенда в заказанных услугах в сделках, в которых заказан этот стенд
     * @param array $data Массив с новыми данными стенда из каталога
     * @since 1.0.9.10
     */
    protected function updateStands(array $data): void
    {
        $old = parent::getItem($data['id']);
        $db =& $this->getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`contractID`, `itemID`, `catalogID`, `contractID`")
            ->from("`#__prj_stands`")
            ->where("`catalogID` = {$data['id']}");
        $stands = $db->setQuery($query)->loadObjectList();
        foreach ($stands as $stand) {
            $cm = AdminModel::getInstance('Catalog', 'ProjectsModel');
            $catalog = $cm->getItem($stand->catalogID);
            $query = $db->getQuery(true);
            $query
                ->update("`#__prj_contract_items`")
                ->set("`value` = `value` - {$old->square} + {$data['square']}")
                ->where("`contractID` = {$stand->contractID}")
                ->where("`itemID` = {$stand->itemID}");
            $db->setQuery($query)->execute();
            $nonify = array();
            $nonify['contractID'] = $stand->contractID;
            $nonify['old_square'] = $old->square;
            $nonify['new_square'] = $data['square'];
            $nonify['stand'] = $catalog->number;
            $this->sendNotify($nonify);
        }
    }

    /**
     * Отправляет менеджеру уведомление об изменении площади стенда
     * @param array $data Массив с данными для уведомления
     * @since 1.0.9.10
     */
    public function sendNotify(array $data): void
    {
        $cm = AdminModel::getInstance('Contract', 'ProjectsModel');
        $em = AdminModel::getInstance('Exhibitor', 'ProjectsModel');
        $pm = AdminModel::getInstance('Project', 'ProjectsModel');
        $tm = AdminModel::getInstance('Todo', 'ProjectsModel');
        $contract = $cm->getItem($data['contractID']);
        $exhibitor = $em->getItem($contract->expID);
        $project = $pm->getItem($contract->prjID);
        $arr = array();
        $arr['id'] = NULL;
        $arr['is_notify'] = 1;
        $arr['contractID'] = $data['contractID'];
        $arr['managerID'] = $contract->managerID;
        $exhibitor = ProjectsHelper::getExpTitle($exhibitor->title_ru_short, $exhibitor->title_ru_full, $exhibitor->title_en);
        $project = $project->title;
        if ($contract->status == '1')
        {
            $number = $contract->number ?? JText::sprintf('COM_PROJECTS_WITHOUT_NUMBER');
            $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_DG_CATALOG_EDITED', $data['stand'], $number, $exhibitor, $project, $data['old_square'], $data['new_square']);
        }
        else
        {
            $arr['task'] = JText::sprintf('COM_PROJECT_TASK_STAND_SD_CATALOG_EDITED', $data['stand'], $exhibitor, $project, $data['old_square'], $data['new_square']);
        }
        $arr['state'] = 0;
        $tm->save($arr);
    }

    protected function prepareTable($table)
    {
        $nulls = array('number', 'categoryID', 'title'); //Поля, которые NULL
        foreach ($nulls as $field)
        {
            if (!strlen($table->$field)) $table->$field = NULL;
        }
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.catalog.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/catalog.js';
    }

    private $id;
}