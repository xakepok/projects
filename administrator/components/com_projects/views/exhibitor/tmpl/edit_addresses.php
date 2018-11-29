<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('addresses') as $field) : ?>
            <div class="control-label">
                <?php echo $field->label; ?>
            </div>
            <div class="controls">
                <?php echo $field->input;
                if (($field->name == 'jform[site]' || $field->name == 'jform[email]') && $field->value != null) {
                    $url = ($field->name == 'jform[site]') ? $field->value : sprintf('mailto:%s', $field->value);
                    echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_GO'), array('target' => '_blank'));
                }
                ?>
            </div>
            <br>
        <?php endforeach; ?>
    </div>
</fieldset>
