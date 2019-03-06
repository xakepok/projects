<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        $dates[] = $dat;
    endforeach;
endforeach;
?>
<tr>
    <td colspan="<?php echo count($dates) + 1;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
