<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $i => $item) :
    ?>
    <tr class="row0">
        <td>
            <?php echo $item['dat']; ?>
        </td>
        <td>
            <?php echo $item['manager']; ?>
        </td>
        <td>
            <?php echo $item['section']; ?>
        </td>
        <td>
            <?php echo $item['action']; ?>
        </td>
        <td>
            <?php echo $item['itemID']; ?>
        </td>
    </tr>
<?php endforeach; ?>
