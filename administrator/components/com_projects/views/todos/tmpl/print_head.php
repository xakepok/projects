<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE_OPEN'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_CONTRACT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_PROJECT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_EXP'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_TASK'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_RESULT'); ?>
    </th>
    <?php if ($this->isAdmin): ?>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_MANAGER'); ?>
        </th>
    <?php endif; ?>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE'); ?>
    </th>
</tr>