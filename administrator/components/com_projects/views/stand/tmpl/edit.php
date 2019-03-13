<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('script', $this->script);
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
$action = JRoute::_('index.php?option=com_projects&amp;view=stand&amp;layout=edit&amp;id=' . (int)$this->item->id);
$return = JFactory::getApplication()->input->get('return', null);
if ($return != null)
{
    $action .= "&amp;return={$return}";
}
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task === 'stand.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>
<form action="<?php echo $action; ?>"
      method="post" name="adminForm" id="adminForm" xmlns="http://www.w3.org/1999/html" class="form-validate">
    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <div class="tab-content">
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::sprintf('COM_PROJECTS_BLANK_STAND')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->loadTemplate('general');?>
                    </div>
                    <div class="span6">
                        <?php echo $this->loadTemplate('scheme');?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php if ($this->names == 'stand'): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'electric', JText::sprintf('COM_PROJECTS_BLANK_STAND_ELECTRIC')); ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->loadTemplate('electric');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'internet', JText::sprintf('COM_PROJECTS_BLANK_STAND_INTERNET')); ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->loadTemplate('internet');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'multimedia', JText::sprintf('COM_PROJECTS_BLANK_STAND_MULTIMEDIA')); ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->loadTemplate('multimedia');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'water', JText::sprintf('COM_PROJECTS_BLANK_STAND_WATER')); ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->loadTemplate('water');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'cleaning', JText::sprintf('COM_PROJECTS_BLANK_STAND_CLEANING')); ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <?php echo $this->loadTemplate('cleaning');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
            </div>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

