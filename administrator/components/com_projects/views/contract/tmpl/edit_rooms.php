<?php
defined('_JEXEC') or die;
$return = base64_encode("index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
$addUrl = JRoute::_("index.php?option=com_projects&amp;task=stand.add&amp;contractID={$this->item->id}&amp;return={$return}");
$addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_ROOM'));
?>
<div>
    <?php echo $addLink; ?>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ROOM_NAME'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_ROOM_ARRIVAL'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_ROOM_DEPARTMENT'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ROOM_CATEGORY'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ROOM_HOTEL'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_MENU_ITEM'); ?>
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
                <td>
                    <?php echo $stand['number']; ?>
                </td>
                <td>
                    <?php echo $stand['arrival']; ?>
                </td>
                <td>
                    <?php echo $stand['department']; ?>
                </td>
                <td>
                    <?php echo $stand['category']; ?>
                </td>
                <td>
                    <?php echo $stand['hotel']; ?>
                </td>
                <td>
                    <?php echo $stand['item']; ?>
                </td>
                <td class="standDel_<?php echo $stand['id']; ?>">
                    <input type="button" value="<?php echo JText::sprintf('COM_PROJECTS_TITLE_REMOVE_ROOM');?>" onclick="removeRoom(<?php echo $stand['id']; ?>);">
                </td>
            </tr>
        </form>
    <?php endforeach; ?>
    </tbody>
</table>
