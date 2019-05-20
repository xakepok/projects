<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$colspan = (ProjectsHelper::canDo('core.general')) ? 11 + count($this->advanced_items) : 10 + count($this->advanced_items);
?>
<tr>
    <td colspan="<?php echo $colspan;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>