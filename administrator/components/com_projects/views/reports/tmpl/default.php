<?php
defined('_JEXEC') or die;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('searchtools.form');

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
$url = "index.php?option=com_projects&amp;view=reports";
if (!empty($this->type)) $url .= "&amp;type=".$this->type;
$action = JRoute::_($url);
$ret = "index.php?option=com_projects&view=reports";
if (!empty($this->type)) $ret .= "&type=".$this->type;
$return = base64_encode($ret);
?>
<div class="row-fluid">
    <div id="j-sidebar-container" class="span2">
        <form action="<?php echo JRoute::_("index.php?return={$return}"); ?>" method="post">
            <?php echo $this->sidebar; ?>
        </form>
    </div>
    <div id="j-main-container" class="span10 j-toggle-main">
        <?php if (!empty($this->type)) :?>
        <div>
            <?php
            $url = "index.php?option=com_projects&amp;task=reports.exportxls&amp;type={$this->type}";
            $url = JRoute::_($url);
            echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_EXPORT_XLS'));
            ?>
        </div>
        <?php endif; ?>
        <form action="<?php echo $action; ?>" method="post"
              name="adminForm" id="adminForm">
            <?php if (!empty($this->type)) echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
            <?php if (!empty($this->type)) :?>
            <table class="table table-striped">
                <thead><?php echo $this->loadTemplate("head_{$this->type}"); ?></thead>
                <tbody><?php echo $this->loadTemplate("body_{$this->type}"); ?></tbody>
                <tfoot><?php echo $this->loadTemplate("foot_{$this->type}"); ?></tfoot>
            </table>
            <?php endif; ?>
            <?php if (empty($this->type)) :?>
                <?php echo $this->loadTemplate("select_type"); ?>
            <?php endif; ?>
            <div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="filter_order"
                       value="<?php echo $this->escape($this->state->get('list.ordering')); ?>"/>
                <input type="hidden" name="filter_order_Dir"
                       value="<?php echo $this->escape($this->state->get('list.direction')); ?>"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>