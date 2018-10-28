<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->activities as $field) :
            $class = array();
            $class['yes'] = "btn";
            $class['no'] = "btn";
            $class[($field['checked']) ? 'yes' : 'no'] .= " active btn-success";
            ?>
            <div class="control-group">
                <div class="control-label">
                    <label id="" for="fieldset_<?php echo $field['id']; ?>" class="hasPopover">
                        <?php echo $field['title']; ?>
                    </label>
                </div>
                <div class="controls">
                    <fieldset id="fieldset_<?php echo $field['id']; ?>"
                              class="btn-group btn-group-yesno btn-group-reversed radio">
                        <input type="radio" id="jform_act_<?php echo $field['id']; ?>_1"
                               name="jform[act][<?php echo $field['id']; ?>]" value="1"
                               <?php if ($field['checked']) echo "selected";?>
                        >
                        <label for="jform_act_<?php echo $field['id']; ?>_1"
                               class="<?php echo $class['yes'];?>"><?php echo JText::_('JYES'); ?></label>
                        <input type="radio" id="jform_act_<?php echo $field['id']; ?>_0"
                               name="jform[act][<?php echo $field['id']; ?>]" value="">
                        <label for="jform_act_<?php echo $field['id']; ?>_0"
                               class="<?php echo $class['no'];?>"><?php echo JText::_('JNO'); ?></label>
                    </fieldset>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</fieldset>
