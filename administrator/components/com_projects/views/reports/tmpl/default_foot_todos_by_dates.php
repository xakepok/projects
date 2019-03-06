<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items['current'] as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!in_array($dat, $dates)) $dates[] = $dat;
    endforeach;
endforeach;
?>
<tr>
    <td colspan="<?php echo count($dates) + 4;?>" class="pagination-centered"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
