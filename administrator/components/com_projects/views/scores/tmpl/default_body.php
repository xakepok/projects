<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = 0;
foreach ($this->items as $i => $item) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_projects.score.' . $item['id']);
    ?>
    <tr class="row0" style="color: <?php echo $item['color'];?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $item['state'], $i, 'scores.', $canChange); ?>
        </td>
        <td>
            <?php echo $item['edit'];?>
        </td>
        <td>
            <?php echo $item['number'];?>
        </td>
        <td>
            <?php echo $item['dat'];?>
        </td>
        <td>
            <?php echo $item['project'];?>
        </td>
        <td>
            <?php echo $item['exp'];?>
        </td>
        <td>
            <?php echo $item['amount'];?>
        </td>
        <td>
            <?php echo $item['payments'];?>
        </td>
        <td>
            <?php echo $item['debt'];?>
        </td>
        <td>
            <?php echo $item['showPaymens'], " / ", $item['doPayment'];?>
        </td>
        <td>
            <?php echo $item['state_text'];?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>