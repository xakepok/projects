<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsPlans extends Table
{
    var $id = null;
    var $prjID = null;
    var $path = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_plans', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}