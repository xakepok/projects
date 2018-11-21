<?php
defined('_JEXEC') or die;
?>
<div class="center"><h4><?php echo JText::sprintf('COM_PROJECTS_BLANK_SUCCESS_PROJECTS');?></h4></div>
<table class="history">
    <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_YEAR');?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_PROJECT');?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_MANAGER');?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_STATUS');?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_CONTRACT');?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_BLANK_TODOS');?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->history['complete'] as $item):?>
        <tr>
            <td>
                <?php echo $item['year'];?>
            </td>
            <td>
                <?php echo $item['project'];?>
            </td>
            <td>
                <?php echo $item['manager'];?>
            </td>
            <td>
                <?php echo $item['status'];?>
            </td>
            <td>
                <?php echo $item['contract'];?>
            </td>
            <td>
                <?php echo $item['todos'];?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
