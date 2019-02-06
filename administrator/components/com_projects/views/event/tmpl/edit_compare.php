<?php
defined('_JEXEC') or die;
$arr_old = json_decode($this->item->old_data, true);
$arr_new = $this->item->params;
$diff_1 = array_diff($arr_old, $arr_new);
$diff_2 = array_diff($arr_new, $arr_old);
$keys = array_keys($arr_old) + array_keys($arr_new);
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th><?php echo JText::sprintf('COM_PROJECTS_HEAD_PARAM');?></th>
        <th><?php echo JText::sprintf('COM_PROJECTS_HEAD_WAS');?></th>
        <th><?php echo JText::sprintf('COM_PROJECTS_HEAD_IS');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($keys as $key) : ?>
    <tr style="color: <?php echo ($arr_old[$key] == $arr_new[$key]) ? 'black' : 'red';?>">
        <td><?php echo $key;?></td>
        <td><?php echo $arr_old[$key];?></td>
        <td><?php echo $arr_new[$key];?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>