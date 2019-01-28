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
            <?php echo $item['tip']; ?>
        </td>
        <td>
            <?php echo $item['title']; ?>
        </td>
        <td>
            <?php echo $item['text']; ?>
        </td>
        <?php if (ProjectsHelper::canDo('core.general')): ?>
            <td>
                <span class="<?php echo $item['manager']; ?>"><?php echo $item['manager']; ?></span>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
