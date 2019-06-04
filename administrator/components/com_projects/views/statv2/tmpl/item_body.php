<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['items'] as $contractID => $item) :
    ?>
    <tr>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo $item['contract']; ?>
        </td>
        <td class="small">
            <?php echo $item['stands']; ?>
        </td>
        <td class="small">
            <?php echo $item['exhibitor']; ?>
        </td>
        <td class="small">
            <?php echo sprintf("%s %s", $item['value'], $item['unit']); ?>
        </td>
        <td class="small">
            <?php echo $item['price']['rub']; ?>
        </td>
        <td class="small">
            <?php echo $item['price']['usd']; ?>
        </td>
        <td class="small">
            <?php echo $item['price']['eur']; ?>
        </td>
    </tr>
<?php endforeach; ?>
