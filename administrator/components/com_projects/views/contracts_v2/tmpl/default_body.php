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
            <?php echo $item['num']; ?>
        </td>
        <td>
            <?php echo $item['dat']; ?>
        </td>
        <td>
            <?php echo $item['stands']; ?>
        </td>
        <td>
            <?php echo $item['edit']; ?>
        </td>
        <td>
            <?php echo $item['project']; ?>
        </td>
        <td>
            <?php echo $item['exhibitor']; ?>
        </td>
        <td>
            <?php echo $item['isCoExp']; ?>
        </td>
        <td>
            <?php echo $item['todos']; ?>
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
            <?php echo $item['payments']; ?>
        </td>
        <td style="color: <?php echo $item['color'];?>">
            <?php echo $item['debt']; ?>
        </td>
    </tr>
<?php endforeach; ?>
