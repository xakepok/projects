<?php
defined('_JEXEC') or die;
$return = base64_encode("index.php?option=com_projects&amp;view=exhibitor&amp;layout=edit&amp;id={$this->item->id}");
if (($this->item->parentID) != null) {
    $url = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$this->item->parentID}&amp;return={$return}");
    $parent = JHtml::link($url, JText::sprintf('COM_PROJECTS_GO_PARENT_COMPANY'), array('target' => '_blank'));
}
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('names') as $field) :
            echo $field->renderField();
        endforeach; ?>
    <?php if (($this->item->parentID) != null): ?>
        <div class="control-label"></div>
        <div class="controls">
            <?php echo $parent;?>
        </div>
    <?php endif;?>
        <?php foreach ($this->form->getFieldset('names2') as $field) :
            echo $field->renderField();
        endforeach; ?>
    </div>
</fieldset>
