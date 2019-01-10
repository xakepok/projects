<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0" style="color: <?php echo ($item['plan'] < 1) ? 'red' : 'black'; ?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['number']; ?>
        </td>
        <td>
            <?php echo $item['dat']; ?>
        </td>
        <td>
            <?php echo $this->items['stands'][$item['id']]; ?>
        </td>
        <td>
            <?php echo $item['edit_link']; ?>
        </td>
        <td>
            <?php echo $item['project']; ?>
        </td>
        <td>
            <?php echo $item['exponent']; ?>
        </td>
        <td>
            <?php echo $item['plan']; ?>
        </td>
        <?php if (ProjectsHelper::canDo('core.general')): ?>
            <td>
                <span class="<?php echo $item['manager']['class']; ?>"><?php echo $item['manager']['title']; ?></span>
            </td>
        <?php endif; ?>
        <td>
            <?php echo $item['status']; ?>
        </td>
        <td>
            <?php echo $item['amount']; ?>
        </td>
        <td>
            <?php echo $item['paid']; ?>
        </td>
        <td>
            <?php echo $item['debt']; ?>
        </td>
    </tr>
<?php endforeach; ?>
