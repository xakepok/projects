<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsContracts extends Table
{
    var $id = null;
    var $prjID = null;
    var $expID = null;
    var $parentID = null;
    var $dat = null;
    var $currency = null;
    var $status = null;
    var $number = null;
    var $state = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_contracts', 'id', $db);
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