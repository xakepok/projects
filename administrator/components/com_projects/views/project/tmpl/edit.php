<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('script', $this->script);
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
$action = JRoute::_('index.php?option=com_projects&amp;view=project&amp;layout=edit&amp;id=' . (int)$this->item->id);
$return = JFactory::getApplication()->input->get('return', null);
if ($return != null)
{
    $action .= "&amp;return={$return}";
}
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task === 'project.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {*/
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
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::sprintf('COM_PROJECTS_BLANK_PROJECT')); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div>
                            <?php echo $this->loadTemplate('general'); ?>
                        </div>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php if ($this->item->id != null): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'price', JText::sprintf('COM_PROJECTS_HEAD_PRICE_LIST')); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $this->loadTemplate('price'); ?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
                <?php if ($this->item->id != null): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'catalog', JText::sprintf('COM_PROJECTS_BLANK_PROJECT_CATALOGS')); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $this->loadTemplate('catalog'); ?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php endif;?>
            </div>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <div>
            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>