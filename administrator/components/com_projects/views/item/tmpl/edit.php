<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('script', $this->script);
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'item.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {*/
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_projects&amp;view=item&amp;layout=edit&amp;id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" xmlns="http://www.w3.org/1999/html" class="form-validate">
    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <div class="tab-content">
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::sprintf('COM_PROJECTS_BLANK_ITEM')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->loadTemplate('general'); ?>
                    </div>
                    <div class="span6">

                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'contact', JText::sprintf('COM_PROJECTS_BLANK_ITEM_COSTS')); ?>
                <div class="row-fluid">
                    <div class="span4">
                        <?php echo $this->loadTemplate('column1'); ?>
                    </div>
                    <div class="span4">
                        <?php echo $this->loadTemplate('column2'); ?>
                    </div>
                    <div class="span4">
                        <?php echo $this->loadTemplate('column3'); ?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <div>
            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

