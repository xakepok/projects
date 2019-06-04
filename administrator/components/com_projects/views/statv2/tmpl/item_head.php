<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th style="width: 1%;">
        №
    </th>
    <th style="width: 10%;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_CONTRACT_DESC'); ?>
    </th>
    <th style="width: 8%">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_EXP_DESC'); ?>
    </th>
    <th style="width: 10%;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT'); ?>
    </th>
    <th style="width: 10%;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'); ?>
    </th>
    <th style="width: 5%;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'); ?>
    </th>
    <th style="width: 8%;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'); ?>
    </th>
</tr>
