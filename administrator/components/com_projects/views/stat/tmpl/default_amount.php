<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$itemID = JFactory::getApplication()->input->getInt('itemID', 0);
$colspan = ($itemID > 0) ? 8 : 8;
?>
<tr>
    <td colspan="<?php echo $colspan;?>" style="text-align: right;" class="small">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?>
    </td>
    <?php if ($itemID != 0): ?>
    <td class="small">
        <?php echo $this->items['cnt'];?>
    </td>
    <?php endif;?>
    <td class="small">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['rub'], 'rub');?>
    </td>
    <td class="small">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['usd'], 'usd');?>
    </td>
    <td class="small">
        <?php echo ProjectsHelper::getCurrency((float) $this->items['amount']['eur'], 'eur');?>
    </td>
</tr>