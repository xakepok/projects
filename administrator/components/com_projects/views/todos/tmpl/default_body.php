<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
foreach ($this->items as $i => $item) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_projects.todo.' . $item['id']);
    ?>
    <tr class="row0<?php if ($item['expired']) echo ' expired';?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo JHtml::_('jgrid.published', $item['state'], $i, 'todos.', $canChange); ?>
        </td>
        <td>
            <?php echo $item['dat']; ?>
        </td>
        <td>
            <?php echo $item['contract']; ?>
        </td>
        <td>
            <?php echo $item['project']; ?>
        </td>
        <td>
            <?php echo $item['exp']; ?>
        </td>
        <td>
            <?php echo $item['task']; ?>
        </td>
        <td>
            <?php echo $item['result']; ?>
        </td>
        <?php if ($this->isAdmin): ?>
            <td>
                <?php echo $item['open']; ?>
            </td>
            <td>
                <?php echo $item['manager']; ?>
            </td>
        <td>
            <?php echo $item['close']; ?>
        </td>
        <?php endif; ?>
        <td>
            <?php echo $item['dat_open']; ?>
        </td>
        <td>
            <?php echo $item['dat_close']; ?>
        </td>
        <td>
            <?php echo $item['state_text']; ?>
        </td>
        <td>
            <?php echo $item['id']; ?>
        </td>
    </tr>
<?php endforeach; ?>