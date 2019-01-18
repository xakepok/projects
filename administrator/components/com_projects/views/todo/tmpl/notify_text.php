<?php
defined('_JEXEC') or die;
?>
<?php foreach ($this->form->getFieldset('names') as $field) :
    if ($field->name != "jform[task]") continue;
    ?>
    <div class="control-label"><?php echo $field->label; ?></div>
    <div class="controls">
        <?php echo $field->value; ?>
    </div>
<?php endforeach; ?>
