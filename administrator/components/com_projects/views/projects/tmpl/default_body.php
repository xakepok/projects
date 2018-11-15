<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $item) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_projects.project.' . $item['id']);
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $item['state'], $i, 'projects.', $canChange); ?>
        </td>
        <td>
            <?php echo $item['title'];?>
        </td>
        <td>
            <?php echo $item['manager'];?>
        </td>
        <td>
            <?php echo $item['group'];?>
        </td>
        <td>
            <?php echo $item['price'];?>
        </td>
        <td>
            <?php echo $item['column'];?>
        </td>
        <td>
            <?php echo $item['date_start'];?>
        </td>
        <td>
            <?php echo $item['date_end'];?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>