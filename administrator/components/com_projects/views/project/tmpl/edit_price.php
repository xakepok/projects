<?php
defined('_JEXEC') or die;
$ii = 0;
$return = base64_encode("index.php?option=com_projects&view=project&layout=edit&id={$this->item->id}");
$url = JRoute::_("index.php?option=com_projects&amp;task=item.add&amp;projectID={$this->item->id}&amp;return={$return}");
echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_ADD_ITEM'));
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th width="1%">
            №
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TITLE'); ?>
        </th>
        <th width="15%">
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEM'); ?>
        </th>
        <th width="15%">
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_COLUMNS'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_SECTION'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_ACTION_DELETE'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->priceItems as $item):?>
        <tr>
            <td class="small">
                <?php echo ++$i; ?>
            </td>
            <td class="small">
                <?php echo $item['title']; ?>
            </td>
            <td class="small">
                <?php echo $item['price']; ?>
            </td>
            <td class="small">
                <?php echo $item['columns']; ?>
            </td>
            <td class="small">
                <?php echo $item['section']; ?>
            </td>
            <td class="small">
                <?php echo $item['unit']; ?>
            </td>
            <td class="small">
                <?php echo $item['delete']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
