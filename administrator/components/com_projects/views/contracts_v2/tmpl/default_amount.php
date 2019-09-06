<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$title = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM_TOTAL');
?>
<tr>
    <td colspan="<?php echo (ProjectsHelper::canDo('projects.access.contracts.full')) ? '12' : '11'; ?>" style="text-align: right;">
        <?php echo $title, " ", JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB');?>
    </td>
    <td>
        <?php echo number_format($this->items['amount']['total']['rub'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['payments']['total']['rub'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['debt']['total']['rub'], 2, ',', " ");?>
    </td>
</tr>
<tr>
    <td colspan="<?php echo (ProjectsHelper::canDo('projects.access.contracts.full')) ? '12' : '11'; ?>" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD');?>
    </td>
    <td>
        <?php echo number_format($this->items['amount']['total']['usd'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['payments']['total']['usd'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['debt']['total']['usd'], 2, ',', " ");?>
    </td>
</tr>
<tr>
    <td colspan="<?php echo (ProjectsHelper::canDo('projects.access.contracts.full')) ? '12' : '11'; ?>" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR');?>
    </td>
    <td>
        <?php echo number_format($this->items['amount']['total']['eur'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['payments']['total']['eur'], 2, ',', " ");?>
    </td>
    <td>
        <?php echo number_format($this->items['debt']['total']['eur'], 2, ',', " ");?>
    </td>
</tr>