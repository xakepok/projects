<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('names') as $field) :
            if (($field->name == 'jform[managerID]' && !$this->setManager) || ($field->name == 'jform[groupID]' && !$this->setGroup)) continue; ?>
            <div class="control-label"><?php echo $field->label; ?></div>
            <div class="controls">
                <?php echo $field->input; ?>
            </div>
            <br>
        <?php endforeach; ?>
    </div>
</fieldset>
