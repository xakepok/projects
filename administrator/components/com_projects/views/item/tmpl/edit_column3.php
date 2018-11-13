<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="center">
        <h5><?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_COLUMN3_LABEL'); ?></h5>
    </div>
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('column3') as $field) :
            echo $field->renderField();
        endforeach; ?>
    </div>
</fieldset>
