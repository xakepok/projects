<?php
defined('_JEXEC') or die;
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=exhibitor&layout=edit&id={$this->item->id}");
if ($this->item->id != null)
{
    $addUrl = JRoute::_("index.php?option=com_projects&amp;task=contract.add&amp;exhibitorID={$this->item->id}&amp;return={$return}");
    $addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_ACTION_OPEN_CONTRACT'), array('style' => 'font-size: 2em;'));
}
?>
<fieldset class="adminform">
    <div class="control-group form-inline">
        <?php foreach ($this->form->getFieldset('bank') as $field) :
            echo $field->renderField();
        endforeach; ?>
    </div>
</fieldset>
<?php if ($this->item->id != null): ?>
<div style="width: 300px; height: 50px; background-color: #ffd0c1; border: 1px solid black; text-align: center; padding-top: 20px;">
    <?php echo $addLink;?>
</div>
<?php endif;?>