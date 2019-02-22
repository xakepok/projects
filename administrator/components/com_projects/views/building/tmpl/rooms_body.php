<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $item) :?>
    <tr class="row0">
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['stand']; ?>
        </td>
        <td>
            <?php echo $item['number_category']; ?>
        </td>
        <td>
            <?php echo $item['hotel']; ?>
        </td>
        <td>
            <?php echo $item['arrival']; ?>
        </td>
        <td>
            <?php echo $item['department']; ?>
        </td>
        <td>
            <?php echo $item['exhibitor']; ?>
        </td>
        <td>
            <?php echo $item['contract']; ?>
        </td>
    </tr>
<?php endforeach; ?>