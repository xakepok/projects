<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsPayments extends Table
{
    var $id = null;
    var $dat = null;
    var $scoreID = null;
    var $pp = null;
    var $amount = null;
    var $created_by = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_payments', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}