<?php
defined('_JEXEC') or die;
?>
<table id="history">
    <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_DATE');?>
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
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->history as $item):?>
        <tr>
            <td>
                <?php echo $item['dat'];?>
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
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
