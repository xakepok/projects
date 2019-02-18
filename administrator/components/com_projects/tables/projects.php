<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsProjects extends Table
{
    var $id = null;
    var $title = null;
    var $title_ru = null;
    var $title_en = null;
    var $managerID = null;
    var $groupID = null;
    var $priceID = null;
    var $catalogID = null;
    var $columnID = null;
    var $date_start = null;
    var $date_end = null;
    var $contract_prefix = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_projects', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}