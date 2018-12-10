<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = 0;
foreach ($this->items as $i => $item) :?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['title'];?>
        </td>
        <td>
            <?php echo $item['price'];?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>