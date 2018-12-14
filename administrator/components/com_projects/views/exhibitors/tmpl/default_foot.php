<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$colspan = (is_numeric($this->state->get('filter.projectinactive'))) ? '5' : '4';?>
<tr>
    <td colspan="<?php echo $colspan;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>