<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
    <td colspan="8" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB');?>
    </td>
    <td colspan="2">
        <?php echo number_format($this->items['amount']['rub'], 2, '.', " ");?>
    </td>
</tr>
<tr>
    <td colspan="8" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD');?>
    </td>
    <td colspan="2">
        <?php echo number_format($this->items['amount']['usd'], 2, '.', " ");?>
    </td>
</tr>
<tr>
    <td colspan="8" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR');?>
    </td>
    <td colspan="2">
        <?php echo number_format($this->items['amount']['eur'], 2, '.', " ");?>
    </td>
</tr>
