<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$dates = array();
foreach ($this->items as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!in_array($dat, $dates)) $dates[] = $dat;
    endforeach;
endforeach;
sort($dates);
?>
<tr>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TMPL_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <?php foreach ($dates as $date) :?>
        <th>
            <?php echo $date; ?>
        </th>
    <?php endforeach; ?>
</tr>
