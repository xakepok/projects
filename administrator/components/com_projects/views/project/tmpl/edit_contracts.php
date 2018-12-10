<?php
defined('_JEXEC') or die;
?>
<table class="addPrice">
    <thead>
    <tr>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_EXP_DESC'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_MANAGER'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_ACTIVE_TODOS_BY_PROJECT'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->contracts as $contract):?>
        <tr>
            <td>
                <?php echo $contract['contract']; ?>
            </td>
            <td>
                <?php echo $contract['exhibitor']; ?>
            </td>
            <td>
                <?php echo $contract['manager']; ?>
            </td>
            <td>
                <?php echo $contract['plan_cnt']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
