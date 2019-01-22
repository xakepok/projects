<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
    <td colspan="4" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB');?>
    </td>
    <td colspan="7">
        <?php echo ProjectsHelper::getCurrency($this->items['amount']['rub'], 'rub');?>
    </td>
</tr>
<tr>
    <td colspan="4" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD');?>
    </td>
    <td colspan="7">
        <?php echo ProjectsHelper::getCurrency($this->items['amount']['usd'], 'usd');?>
    </td>
</tr>
<tr>
    <td colspan="4" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR');?>
    </td>
    <td colspan="7">
        <?php echo ProjectsHelper::getCurrency($this->items['amount']['eur'], 'eur');?>
    </td>
</tr>
