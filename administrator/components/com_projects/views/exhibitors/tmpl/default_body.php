<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $i => $item) :
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['title'];?>
        </td>
        <?php
        $projectinactive = $this->state->get('filter.projectinactive');
        if (is_numeric($projectinactive)) :?>
            <td>
                <?php echo $item['contract'];?>
            </td>
        <?php endif;?>
        <td>
            <?php echo $item['region'];?>
        </td>
    </tr>
<?php endforeach; ?>