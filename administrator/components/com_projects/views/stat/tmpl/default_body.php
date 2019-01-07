<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo ($this->itemID != 0) ? $item['contract'] : $item['application']; ?>
        </td>
        <?php if ($this->itemID != 0): ?>
            <th>
                <?php echo $item['stands']; ?>
            </th>
        <?php endif;?>        <td>
            <?php echo $item['title']; ?>
        </td>
        <td class="small">
            <?php echo $item['unit']; ?>
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
        <td class="small">
        <?php echo $item['value']; ?>
        </td>
        <td class="small">
            <?php echo $item['amount']['rub']; ?>
        </td>
        <td class="small">
            <?php echo $item['amount']['usd']; ?>
        </td>
        <td class="small">
            <?php echo $item['amount']['eur']; ?>
        </td>
    </tr>
<?php endforeach; ?>