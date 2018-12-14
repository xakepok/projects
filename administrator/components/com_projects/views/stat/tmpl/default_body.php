<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0<?php if ($item['expired']) echo ' expired'; ?>">
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['section']; ?>
        </td>
        <td>
            <?php echo $item['title']; ?>
        </td>
        <td>
            <?php echo $item['unit']; ?>
        </td>
        <td>
            <?php echo $item['unit_2']; ?>
        </td>
        <td>
            <?php echo $item['price']['rub']; ?>
        </td>
        <td>
            <?php echo $item['price']['usd']; ?>
        </td>
        <td>
            <?php echo $item['price']['eur']; ?>
        </td>
        <td>
        <?php echo $item['value']; ?>
        </td>
        <td>
            <?php echo $item['amount']['rub']; ?>
        </td>
        <td>
            <?php echo $item['amount']['usd']; ?>
        </td>
        <td>
            <?php echo $item['amount']['eur']; ?>
        </td>
    </tr>
<?php endforeach; ?>