<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('names') as $field) :
            if (($field->name == 'jform[managerID]' && !$this->setManager) || ($field->name == 'jform[groupID]' && !$this->setGroup)) continue;
            echo $field->renderField();
        endforeach; ?>
    </div>
</fieldset>
