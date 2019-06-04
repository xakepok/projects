<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
?>
<tr>
    <td colspan="5" style="text-align: right;" class="small"><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?></td>
    <td class="small"><?php echo ProjectsHelper::getCurrency((float) $this->items['sum']['rub'], 'rub');?></td>
    <td class="small"><?php echo ProjectsHelper::getCurrency((float) $this->items['sum']['usd'], 'usd');?></td>
    <td class="small"><?php echo ProjectsHelper::getCurrency((float) $this->items['sum']['eur'], 'eur');?></td>
</tr>
