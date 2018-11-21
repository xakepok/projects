<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('names') as $field) :
            if ($field->name == 'jform[number]' && (!ProjectsHelper::canDo('projects.contract.allow') || $field->value == null)) continue;
            if ($this->item->id == null && ($field->name == 'jform[managerID]' || $field->name == 'jform[dat]')) continue;
            ?>
            <div class="control-label"><?php echo $field->label; ?></div>
            <div class="controls">
                <?php echo $field->input; ?>
            </div>
            <br>
        <?php endforeach; ?>
    </div>
</fieldset>
