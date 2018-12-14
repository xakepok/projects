<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
    <td colspan="9" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_AMOUNT');?>
    </td>
    <td>
        <?php echo number_format($this->items['amount']['rub'], 0, '.', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['payments']['rub'], 0, '.', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['debt']['rub'], 0, '.', " ");?>
    </td>
</tr>