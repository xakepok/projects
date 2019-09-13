<?php
defined('_JEXEC') or die;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('searchtools.form');
JHtml::_('bootstrap.tooltip');

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
$return = ProjectsHelper::getReturnUrl();
?>
<div class="row-fluid">
    <div id="j-sidebar-container" class="span2">
        <form action="<?php echo ProjectsHelper::getSidebarAction(); ?>" method="post">
            <?php echo $this->sidebar; ?>
        </form>
    </div>
    <div id="j-main-container" class="span10 big-table">
        <form action="<?php echo ProjectsHelper::getActionUrl(); ?>" method="post"
              name="adminForm" id="adminForm">
            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
            <table class="table table-striped">
                <div><?php //echo JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=contracts_v2.exportxls"),JText::sprintf('COM_PROJECTS_ACTION_EXPORT_XLS')) ;?></div>
                <thead><?php echo $this->loadTemplate('head'); ?></thead>
                <?php if (is_numeric(ProjectsHelper::getActiveProject()) && $this->userSettings['contracts_v2-position_total'] == 0) echo $this->loadTemplate('amount'); ?>
                <tbody><?php echo $this->loadTemplate('body'); ?></tbody>
                <?php if (is_numeric(ProjectsHelper::getActiveProject()) && $this->userSettings['contracts_v2-position_total'] == 1) echo $this->loadTemplate('amount'); ?>
                <tfoot><?php echo $this->loadTemplate('foot'); ?></tfoot>
            </table>
            <?php // load the modal for displaying the batch options
            echo JHtml::_(
                'bootstrap.renderModal',
                'collapseModal',
                array(
                    'title' => JText::sprintf('COM_PROJECTS_BATCH_TITLE_SETTINGS'),
                    'footer' => $this->loadTemplate('batch_footer')
                ),
                $this->loadTemplate('batch_body')
            ); ?>
            <div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>
    </div>
</div>