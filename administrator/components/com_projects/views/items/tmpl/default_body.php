<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = 0;
foreach ($this->items as $i => $item) :?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo $item['title'];?>
        </td>
        <td class="small">
            <?php echo $item['price_rub'];?>
        </td>
        <td class="small">
            <?php echo $item['price_usd'];?>
        </td>
        <td class="small">
            <?php echo $item['price_eur'];?>
        </td>
        <td class="small">
            <?php echo $item['column_1'];?>
        </td>
        <td class="small">
            <?php echo $item['column_2'];?>
        </td>
        <td class="small">
            <?php echo $item['column_3'];?>
        </td>
        <td class="small">
            <?php echo $item['section'];?>
        </td>
        <td class="small">
            <?php echo $item['price'];?>
        </td>
        <td class="small">
            <?php echo $item['unit'];?>
        </td>
        <td class="small">
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>