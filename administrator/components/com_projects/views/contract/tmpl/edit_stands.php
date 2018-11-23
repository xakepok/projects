<?php
defined('_JEXEC') or die;
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
$addUrl = JRoute::_("index.php?option=com_projects&amp;task=stand.add&amp;contractID={$this->item->id}&amp;return={$return}");
$addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_STAND'));
?>
<div>
    <?php echo $addLink; ?>
</div>
<table>
    <thead>
    <tr>
        <th>
            <?php echo 'ID'; ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_TYPE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_TYPE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
        </th>
        <th>

        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->item->stands as $stand):
        ?>
        <form action="<?php echo $stand['action']; ?>" method="post"
              id="form_task_<?php echo $stand['id']; ?>">
            <tr id="row_stand_<?php echo $stand['id']; ?>">
                <td style="color:<?php echo ($stand['expired']) ? 'red' : 'black';?>">
                    <?php echo $stand['id']; ?>
                </td>
                <td>
                    <?php echo $stand['tip']; ?>
                </td>
                <td>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
                </td>
                <td class="standDel_<?php echo $stand['id']; ?>">
                    <input type="text" name="stand_<?php echo $stand['id']; ?>" value="<?php echo $stand['number']; ?>" />
                    <input type="button" value="<?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_STAND');?>" onclick="removeStand(<?php echo $stand['id']; ?>);">
                </td>
            </tr>
        </form>
    <?php endforeach; ?>
    </tbody>
</table>
