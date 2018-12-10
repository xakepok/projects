<?php
defined('_JEXEC') or die;
jimport('joomla.form.formrule');
class JFormRuleStandnumber extends JFormRule
{
    protected $regex = '^[A-Z0-9-]+$';
}