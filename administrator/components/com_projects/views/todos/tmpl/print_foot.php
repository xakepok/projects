<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$colspan = ($this->isAdmin) ? '10' : '9';
?>
<tr>
    <td colspan="<?php echo $colspan;?>" class="pagination-centered"></td>
</tr>