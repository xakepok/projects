<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
    <td colspan="8" style="text-align: right; font-style: italic;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?>
    </td>
    <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
        <td class="small">
            <?php echo $this->items['sum'][$item_id] ?? 0; ?>
        </td>
    <?php endforeach;?>
</tr>
