<?php
defined('_JEXEC') or die; ?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('contracts_v2') as $field) :?>
            <?php echo $field->renderField();?>
        <?php endforeach; ?>
    </div>
</fieldset>

