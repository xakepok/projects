<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsContracts extends Table
{
    var $id = null;
    var $prjID = null;
    var $expID = null;
    var $managerID = null;
    var $parentID = null;
    var $dat = null;
    var $currency = null;
    var $isCoExp = null;
    var $status = null;
    var $number = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_contracts', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}