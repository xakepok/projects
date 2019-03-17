<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $i => $item) :?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
        <td>
            <?php echo $item['title_old'];?>
        </td>
        <td>
            <?php echo $item['variants'];?>
        </td>
        <td>
            <?php echo $item['exhibitorID'];?>
        </td>
    </tr>
<?php endforeach; ?>