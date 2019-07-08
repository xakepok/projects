<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
?>
<div><h5><?php echo $this->item['title'];?></h5></div>
<div>
    <?php echo $this->loadTemplate("{$this->tmpl}-columns");?>
</div>
