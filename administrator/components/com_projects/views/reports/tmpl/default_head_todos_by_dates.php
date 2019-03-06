<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$dates = array();
foreach ($this->items as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        $dates[] = $dat;
    endforeach;
endforeach;
sort($dates);
?>
<tr>
    <th>
        <?php echo $manager; ?>
    </th>
    <?php foreach ($dates as $date) :?>
        <th>
            <?php echo $date; ?>
        </th>
    <?php endforeach; ?>
</tr>
