<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
$cnts = array();
foreach ($this->items as $manager => $statuses) {
    foreach ($statuses as $status => $cnt) {
        if (!in_array($status, $cnts)) $cnts[] = $status;
    }
}
foreach ($this->items as $manager => $statuses) :
    ?>
    <tr>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo $manager; ?>
        </td>
        <?php foreach ($cnts as $status) :?>
            <td>
                <?php echo $this->items[$manager][$status] ?? 0;?>
            </td>
        <?php endforeach;?>
    </tr>
<?php endforeach; ?>