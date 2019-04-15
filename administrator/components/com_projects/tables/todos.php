<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/**
 * Таблица планировщика для сделок
 * @since   1.2.3
 * @version 1.2.3
 */

class TableProjectsTodos extends Table
{
    var $id = null;
    var $is_notify = null;
    var $dat = null;
    var $dat_open = null;
    var $dat_close = null;
    var $contractID = null;
    var $managerID = null;
    var $task = null;
    var $result = null;
    var $userOpen = null;
    var $userClose = null;
    var $state = null;
    var $notify_group = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_todos', 'id', $db);
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