<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/**
 * Таблица лога действий пользователей
 * @since   1.1.0.1
 */

class TableProjectsUal extends Table
{
    var $id = null;
    var $dat = null;
    var $userID = null;
    var $section = null;
    var $action = null;
    var $itemID = null;
    var $params = null;
    var $old_data = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_user_action_log', 'id', $db);
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