<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$title = JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM_TOTAL');
$colspan = $this->columnsCount - 3;
?>
<tr>
    <td colspan="<?php echo $colspan; ?>" style="text-align: right;">
        <?php echo $title, " ", JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB');?>
    </td>
    <td>
        <?php echo $this->items['total']['amounts']['rub'];?>
    </td>
    <td>
        <?php echo $this->items['total']['payments']['rub'];?>
    </td>
    <td>
        <?php echo $this->items['total']['debt']['rub'];?>
    </td>
</tr>
<tr>
    <td colspan="<?php echo $colspan; ?>" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD');?>
    </td>
    <td>
        <?php echo $this->items['total']['amounts']['usd'];?>
    </td>
    <td>
        <?php echo $this->items['total']['payments']['usd'];?>
    </td>
    <td>
        <?php echo $this->items['total']['debt']['usd'];?>
    </td>
</tr>
<tr>
    <td colspan="<?php echo $colspan; ?>" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR');?>
    </td>
    <td>
        <?php echo $this->items['total']['amounts']['eur'];?>
    </td>
    <td>
        <?php echo $this->items['total']['payments']['eur'];?>
    </td>
    <td>
        <?php echo $this->items['total']['debt']['eur'];?>
    </td>
</tr>