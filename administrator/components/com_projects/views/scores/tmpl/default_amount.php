<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
    <td colspan="7" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['rub'], 'rub');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['payments']['rub'], 'rub');?>
    </td>
    <td colspan="3">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['debt']['rub'], 'rub');?>
    </td>
</tr>
<tr>
    <td colspan="7" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['usd'], 'usd');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['payments']['usd'], 'usd');?>
    </td>
    <td colspan="3">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['debt']['usd'], 'usd');?>
    </td>
</tr>
<tr>
    <td colspan="7" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['eur'], 'eur');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['payments']['eur'], 'eur');?>
    </td>
    <td colspan="3">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['debt']['eur'], 'eur');?>
    </td>
</tr>