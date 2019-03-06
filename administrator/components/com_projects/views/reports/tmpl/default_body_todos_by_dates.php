<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
$managers = array();
$dates = array();
foreach ($this->items as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!in_array($manager, $managers)) $managers[] = $manager;
        if (!in_array($dat, $dates)) $dates[] = $dat;
    endforeach;
endforeach;
sort($dates);
foreach ($managers as $manager) :
    ?>
<tr>
    <td>
        <?php echo $manager;?>
    </td>
    <?php
    foreach ($dates as $date) :?>
        <td>
            <?php echo $this->items[$date][$manager] ?? 0;?>
        </td>
    <?php endforeach; ?>
</tr>
<?php
endforeach;