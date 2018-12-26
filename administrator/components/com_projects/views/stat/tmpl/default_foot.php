<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$columns = ($this->itemID != 0) ? '12' : '11';
?>
<tr>
    <td colspan="12" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>