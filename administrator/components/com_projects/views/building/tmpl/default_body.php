<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = 0;
foreach ($this->items as $i => $item) :?>
    <tr class="row0<?php if ($item['expired']) echo ' expired';?>">
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['contract']; ?>
        </td>
        <td>
            <?php echo $item['exhibitor']; ?>
        </td>
        <td>
            <?php echo $item['stands']; ?>
        </td>
        <td>
            <?php echo $item['freeze']; ?>
        </td>
    </tr>
<?php endforeach; ?>