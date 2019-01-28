<?php
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
use Joomla\CMS\HTML\HTMLHelper;
HTMLHelper::_('script', $this->script);
HTMLHelper::_('script', 'com_projects/jquery.maskedinput.min.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
$action = 'index.php?option=com_projects&amp;view=person&amp;layout=edit&amp;id=' . (int)$this->item->id;
$return = urlencode(JFactory::getApplication()->input->get('return', null));
if ($return != null)
{
    $action .= "&amp;return={$return}";
}
$action = JRoute::_($action);
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task === 'person.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {*/
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
    }
</script>
<form action="<?php echo $action; ?>"
      method="post" name="adminForm" id="adminForm" xmlns="http://www.w3.org/1999/html" class="form-validate">
    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::sprintf('COM_PROJECTS_BLANK_PERSON');?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset class="adminform">
                        <div class="control-group form-inline">
                            <?php foreach ($this->form->getFieldset('names') as $field) :?>
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls">
                                    <?php echo $field->input; ?>
                                    <?php
                                    if ($field->name == 'jform[phone_work]' || $field->name == 'jform[phone_mobile]')
                                    {
                                        $checked = "";
                                        if ($field->value !== "")
                                        {
                                            if (stripos($field->value, "+7") !== false)
                                            {
                                                $checked = " checked";
                                            }
                                            else
                                            {
                                                $checked = "";
                                            }
                                        }
                                        else
                                        {
                                            $checked = " checked";
                                        }
                                        echo "<input type='checkbox' onclick='setMask(this.id, \"{$field->id}\")' id='mask_{$field->name}' value='1'{$checked} />";
                                        echo "<label for='mask_{$field->name}'>", JText::sprintf('COM_PROJECTS_HEAD_EXP_CONTACT_PHONE_MASK'), "</label>";
                                    }
                                    ?>
                                </div>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>

