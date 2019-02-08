<?php
defined('_JEXEC') or die;
$return = base64_encode("index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
$addUrl = JRoute::_("index.php?option=com_projects&amp;task=stand.add&amp;contractID={$this->item->id}&amp;return={$return}");
$addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_STAND'));
?>
<div>
    <?php echo $addLink; ?>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>
            <?php echo 'ID'; ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_TYPE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_HEAD_SQUARE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_MENU_ITEM'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_FREEZE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_COMMENT'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_STATUS'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_SCHEME_DESC'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_STAND'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->item->stands as $stand): ?>
        <form action="<?php echo $stand['action']; ?>" method="post"
              id="form_task_<?php echo $stand['id']; ?>">
            <tr id="row_stand_<?php echo $stand['id']; ?>">
                <td style="color:<?php echo ($stand['expired']) ? 'red' : 'black';?>">
                    <?php echo $stand['id']; ?>
                </td>
                <td>
                    <?php echo $stand['number']; ?>
                </td>
                <td>
                    <?php echo $stand['tip']; ?>
                </td>
                <td>
                    <?php echo $stand['sq']; ?>
                </td>
                <td>
                    <?php echo $stand['item']; ?>
                </td>
                <td>
                    <?php echo $stand['freeze']; ?>
                </td>
                <td>
                    <?php echo $stand['comment']; ?>
                </td>
                <td>
                    <?php echo $stand['status']; ?>
                </td>
                <td>
                    <?php echo $stand['scheme']; ?>
                </td>
                <td class="standDel_<?php echo $stand['id']; ?>">
                    <input type="button" value="<?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_STAND');?>" onclick="removeStand(<?php echo $stand['id']; ?>);">
                </td>
            </tr>
        </form>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="10" style="font-style: italic;">
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_IS_DELEGATED_HINT');?>
        </td>
    </tr>
    </tfoot>
</table>
