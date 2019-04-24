<?php
defined('_JEXEC') or die;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('searchtools.form');

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
$return = base64_encode("index.php?option=com_projects&view=contracts");
?>
<div class="row-fluid">
    <div id="j-sidebar-container" class="span2">
        <form action="<?php echo JRoute::_("index.php?return={$return}"); ?>" method="post">
            <?php echo $this->sidebar; ?>
        </form>
    </div>
    <div id="j-main-container" class="span10">
        <form action="<?php echo JRoute::_('index.php?option=com_projects&amp;view=contracts'); ?>" method="post"
              name="adminForm" id="adminForm">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
            <table class="table table-striped">
                <div><?php echo JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=contracts.exportxls"),JText::sprintf('COM_PROJECTS_ACTION_EXPORT_XLS')) ;?></div>
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
                <?php echo $this->loadTemplate('amount'); ?>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
            </table>
            <div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>