<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_APPLICATION', 'application', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_TITLE', 'p.title_ru', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_2'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'); ?>
    </th>
</tr>