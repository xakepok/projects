<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$columns = count($this->items) + 1;
?>
<tr>
    <td colspan="<?php echo $columns;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>