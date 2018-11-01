<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $item) :
    //$canChange = JFactory::getUser()->authorise('core.edit.state', 'com_projects.price.' . $item['id']);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo $item['project'];?>
        </td>
        <td>
            <?php echo $item['exponent'];?>
        </td>
        <td>
            <?php echo $item['dat'];?>
        </td>
        <td>
            <?php echo $item['manager'];?>
        </td>
        <td>
            <?php echo $item['status'];?>
        </td>
        <td>
            <?php echo $item['amount'];?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>