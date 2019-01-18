<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$itemID = JFactory::getApplication()->input->getInt('itemID', 0);
$colspan = ($itemID > 0) ? 9 : 8;
?>
<tr>
    <td colspan="<?php echo $colspan;?>" style="text-align: right;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['rub'], 'rub');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['usd'], 'usd');?>
    </td>
    <td>
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['eur'], 'eur');?>
    </td>
</tr>