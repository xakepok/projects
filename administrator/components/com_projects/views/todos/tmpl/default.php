<?php
defined('_JEXEC') or die;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('searchtools.form');
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">
    <form action="<?php echo JRoute::_('index.php?option=com_projects&amp;view=todos'); ?>" method="post" name="adminForm" id="adminForm">
        <div class="js-stools clearfix">
            <?php echo $this->loadTemplate('filter');?>
        </div>
        <div class="clearfix"></div>
        <table class="table table-striped">
            <thead><?php echo $this->loadTemplate('head');?></thead>
            <tbody><?php echo $this->loadTemplate('body');?></tbody>
            <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        </table>
        <div>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
