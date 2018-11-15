<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsProjects extends Table
{
    var $id = null;
    var $title = null;
    var $title_ru = null;
    var $title_en = null;
    var $managerID = null;
    var $groupID = null;
    var $priceID = null;
    var $columnID = null;
    var $date_start = null;
    var $date_end = null;
    var $state = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_projects', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }

    public function publish($pks = null, $state = 1, $userId = 0)
    {
        $k = $this->_tbl_key;

        // Очищаем входные параметры.
        JArrayHelper::toInteger($pks);
        $state = (int) $state;

        // Если первичные ключи не установлены, то проверяем ключ в текущем объекте.
        if (empty($pks))
        {
            if ($this->$k)
            {
                $pks = array($this->$k);
            }
            else
            {
                throw new RuntimeException(JText::sprintf('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'), 500);
            }
        }

        // Устанавливаем состояние для всех первичных ключей.
        foreach ($pks as $pk)
        {
            // Загружаем сообщение.
            if (!$this->load($pk))
            {
                throw new RuntimeException(JText::sprintf('COM_PROJECTS_ERROR_RECORD_LOAD'), 500);
            }

            $this->state = $state;

            // Сохраняем сообщение.
            if (!$this->store())
            {
                throw new RuntimeException(JText::sprintf('COM_PROJECTS_TABLE_ERROR_RECORD_STORE'), 500);
            }
        }

        return true;
    }

}