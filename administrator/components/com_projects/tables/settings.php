<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/**
 * Таблица шаблонов заданий для планировщика
 * @since   1.1.0.1
 */

class TableProjectsSettings extends Table
{
    var $id = null;
    var $userID = null;
    var $params = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_user_settings', 'id', $db);
    }

    public function load($keys = null, $reset = true)
    {
        return parent::load($keys, $reset);
    }

    public function bind($src, $ignore = array())
    {
        foreach ($src as $field => $value)
        {
            if (isset($this->$field)) $this->$field = $value;
        }
        return parent::bind($src, $ignore);
    }
    public function save($src, $orderingFilter = '', $ignore = '')
    {
        return parent::save($src, $orderingFilter, $ignore);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}