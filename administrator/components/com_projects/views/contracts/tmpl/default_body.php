<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = 0;
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0" style="color: <?php echo ($item['plan'] < 1) ? 'red' : 'black';?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['number'];?>
        </td>
        <td>
            <?php echo $this->items['stands'][$item['id']];?>
        </td>
        <td>
            <?php echo $item['edit_link'];?>
        </td>
        <td>
            <?php echo $item['project'];?>
        </td>
        <td>
            <?php echo $item['exponent'];?>
        </td>
        <td>
            <?php echo $item['plan'];?>
        </td>
        <td>
            <?php echo $item['dat'];?>
        </td>
        <td>
            <span class="<?php echo $item['manager']['class'];?>"><?php echo $item['manager']['title'];?></span>
        </td>
        <td>
            <span class="<?php echo $item['group']['class'];?>"><?php echo $item['group']['title'];?></span>
        </td>
        <td>
            <?php echo $item['status'];?>
        </td>
        <td>
            <?php echo $item['amount'];?>
        </td>
        <td>
            <?php echo $item['debt'];?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>