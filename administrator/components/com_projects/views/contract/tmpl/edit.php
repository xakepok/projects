<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('script', $this->script);
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
$action = JRoute::_('index.php?option=com_projects&amp;view=contract&amp;layout=edit&amp;id=' . (int)$this->item->id);
$return = JFactory::getApplication()->input->get('return', null);
if ($return != null)
{
    $action .= "&amp;return={$return}";
}
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task === 'contract.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {*/
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
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::sprintf('COM_PROJECTS_BLANK_CONTRACT')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->loadTemplate('general');?>
                    </div>
                    <div class="span6">
                        <?php if ($this->item->id != null): ?>
                            <?php echo $this->loadTemplate('links');?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php if ($this->item->id != null): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'priceAdd', JText::sprintf('COM_PROJECTS_BLANK_CONTRACT_PRICE_ADD')); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $this->loadTemplate('price');?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->id != null): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'priceCurrent', JText::sprintf('COM_PROJECTS_BLANK_CONTRACT_PRICE_CURRENT')); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $this->loadTemplate('summary');?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->id != null): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'todos', JText::sprintf('COM_PROJECTS_BLANK_TODOS')); ?>
                    <div class="row-fluid">
                        <div>
                            <?php echo $this->loadTemplate('todos');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->id != null): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'files', JText::sprintf('COM_PROJECTS_BLANK_FILES')); ?>
                    <div class="row-fluid">
                        <div>
                            <?php echo $this->loadTemplate('files');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->id != null): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'stands', JText::sprintf('COM_PROJECTS_BLANK_STANDS')); ?>
                    <div class="row-fluid">
                        <div>
                            <?php echo $this->loadTemplate('stands');?>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->finanses != null): ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'finanses', JText::sprintf('COM_PROJECTS_BLANK_FINANSES')); ?>
                    <div class="row-fluid">
                        <div>
                            <?php echo $this->loadTemplate('finanses');?>
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

