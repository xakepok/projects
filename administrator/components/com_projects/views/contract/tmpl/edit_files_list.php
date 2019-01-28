<?php
defined('_JEXEC') or die;
$return = base64_encode("index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
$addUrl = JRoute::_("index.php?option=com_projects&amp;task=todo.add&amp;contractID={$this->item->id}&amp;return={$return}");
$addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO'));
?>
<div>
    <fieldset class="adminform">
        <div class="control-group form-inline">
            <?php foreach ($this->form->getFieldset('files') as $field):
                echo $field->renderField();
            endforeach; ?>
        </div>
    </fieldset>
</div>
