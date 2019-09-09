<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$colspan = ('projects.access.contracts.full') ? '16' : '15';
?>
<tr>
    <td colspan="<?php echo $colspan;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
<tr>
    <td colspan="<?php echo $colspan;?>" style="font-style: italic;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_IS_DELEGATED_HINT');?>
    </td>
</tr>