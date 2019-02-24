<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/**
 * Таблица с информацией о сделках
 * @since   1.1.3
 * @version 1.1.3
 */

class TableProjectsCtrinfo extends Table
{
    var $id = null;
    var $projectID = null;
    var $tip = null;
    var $columnID = null;
    var $priceID = null;
    var $catalogID = null;
    var $currency = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_exp_act', 'id', $db);
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