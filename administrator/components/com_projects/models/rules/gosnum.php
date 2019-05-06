<?php
defined('_JEXEC') or die;
jimport('joomla.form.formrule');
class JFormRuleGosnum extends JFormRule
{
    protected $regex = '^([A-Z0-9]){5,10}$';
}