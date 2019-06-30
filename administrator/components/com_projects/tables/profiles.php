<?php
use Joomla\CMS\Table\Table;
defined('_JEXEC') or die;

class TableProjectsProfiles extends Table
{
    var $id = null;
    var $regID = null;
    var $regID_fact = null;
    var $tip = null;
    var $title_ru_short = null;
    var $title_ru_full = null;
    var $title_en = null;
    var $inn = null;
    var $kpp = null;
    var $rs = null;
    var $ks = null;
    var $bank = null;
    var $bik = null;
    var $city = null;

    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__prj_profiles', 'id', $db);
    }

    public function store($updateNulls = true)
    {
        return parent::store(true);
    }
}