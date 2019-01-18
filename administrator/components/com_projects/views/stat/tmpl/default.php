<?php
defined('_JEXEC') or die;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('searchtools.form');

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
$itemID = JFactory::getApplication()->input->getInt('itemID', 0);
$action = JRoute::_(($itemID != 0) ? "index.php?option=com_projects&amp;view=stat&amp;itemID={$itemID}" : "index.php?option=com_projects&amp;view=stat");
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=todos");
?>
<div class="row-fluid">
    <div id="j-sidebar-container" class="span2">
        <form action="<?php echo JRoute::_("index.php?return={$return}"); ?>" method="post">
            <?php echo $this->sidebar; ?>
        </form>
    </div>
    <div id="j-main-container" class="span10 j-toggle-main">
        <div>
            <?php
            $url = "index.php?option=com_projects&amp;task=stat.exportxls";
            if ($this->itemID != 0) $url .= "&amp;itemID={$this->itemID}";
            $url = JRoute::_($url);
            if (!is_array($this->state->get('filter.item')) || empty($this->state->get('filter.item'))) echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_EXPORT_XLS'));
            ?>
        </div>
        <form action="<?php echo $action; ?>" method="post"
              name="adminForm" id="adminForm">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
            <table class="table table-striped">
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
                <?php echo $this->loadTemplate('amount'); ?>
            </table>
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