<?php
defined('_JEXEC') or die;
$ii = 0;
$return = base64_encode("index.php?option=com_projects&view=project&layout=edit&id={$this->item->id}");
$url = JRoute::_("index.php?option=com_projects&amp;task=catalog.add&amp;projectID={$this->item->id}&amp;return={$return}");
echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_ADD_STAND_TO_CAT'));
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th width="1%">
            №
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER_OR_NUMBER'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_HEAD_SQUARE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_COMPANY'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_ACTION_DELETE'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->catalogItems as $item):?>
        <tr>
            <td>
                <?php echo ++$i; ?>
            </td>
            <td>
                <?php echo $item['title']; ?>
            </td>
            <td>
                <?php echo $item['square']; ?>
            </td>
            <td>
                <?php echo $item['exhibitor']; ?>
            </td>
            <td>
                <?php echo $item['delete']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
