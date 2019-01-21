<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $i => $item) :
    $canChange = JFactory::getUser()->authorise('core.edit.state', 'com_projects.todo.' . $item['id']);
    ?>
    <tr class="row0<?php if ($item['expired']) echo ' expired'; ?>">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['dat_open']; ?>
        </td>
        <td>
            <?php echo $item['task']; ?>
        </td>
        <td>
            <?php echo $item['contract']; ?>
        </td>
        <td>
            <?php echo $item['project']; ?>
        </td>
        <td>
            <?php echo $item['exp']; ?> /
            <a href="#modalCard" data-toggle="modal"
               onclick="showCard(<?php echo $item['expID']; ?>); return true;"><?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CARD'); ?></a>
        </td>
    </tr>
<?php endforeach; ?>