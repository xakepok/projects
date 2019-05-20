<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0">
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
            <?php echo $item['stand']; ?>
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
        <?php if (ProjectsHelper::canDo('projects.access.contracts.full')): ?>
            <td>
                <?php echo $item['manager']; ?>
            </td>
        <?php endif; ?>
        <td>
            <?php echo $item['status']; ?>
        </td>
        <td>
            <?php echo $item['doc_status']; ?>
        </td>
        <td>
            <?php echo $item['amount']; ?>
        </td>
        <td>
            <?php echo $item['paid']; ?>
        </td>
        <td style="color: <?php echo $item['color'];?>">
            <?php echo $item['debt']; ?>
        </td>
    </tr>
<?php endforeach; ?>
