<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/**
 * Таблица истории взаимодействий менеджеров с контрактами
 * @since   1.2.6
 * @version 1.2.6
 */

class TableProjectsHistory extends Table
{
    var $id = null;
    var $dat = null;
    var $contractID = null;
    var $managerID = null;
    var $status = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_exp_history', 'id', $db);
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