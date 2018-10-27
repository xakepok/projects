<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

/*
 * Таблица банковских данных об экспоненте
 */

class TableProjectsBanks extends Table
{
    var $id = null;
    var $exbID = null;
    var $inn = null;
    var $kpp = null;
    var $rs = null;
    var $ks = null;
    var $bank = null;
    var $bik = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_exp_bank', 'id', $db);
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