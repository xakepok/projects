<?php
defined('_JEXEC') or die;
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
$addUrl = JRoute::_("index.php?option=com_projects&amp;task=todo.add&amp;contractID={$this->item->id}&amp;return={$return}");
$addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_TITLE_NEW_TODO'));
?>
<div>
    <?php echo $addLink; ?>
</div>
<table>
    <thead>
    <tr>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_DATE'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_TASK'); ?>
        </th>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_RESULT'); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->todos as $todo):
        ?>
        <form action="<?php echo $todo['action']; ?>" method="post"
              id="form_task_<?php echo $todo['id']; ?>">
            <tr>
                <td style="color:<?php echo ($todo['expired']) ? 'red' : 'black';?>">
                    <?php echo $todo['dat']; ?>
                </td>
                <td>
                    <?php echo $todo['task']; ?>
                </td>
                <td class="resultTodo_<?php echo $todo['id']; ?>">
                    <?php if ($todo['state'] != 1): ?>
                        <input type="text" value="" name="result_<?php echo $todo['id']; ?>" />
                        <input type="button" onclick="closeTask(<?php echo $todo['id']; ?>);" value="<?php echo JText::sprintf('COM_PROJECTS_ACTION_TODO_CLOSE');?>">
                    <?php endif;?>
                    <?php if ($todo['state'] == 1): ?>
                        <?php echo $todo['dat'], ": ", $todo['user'], " ", $todo['result'];?>
                    <?php endif;?>
                </td>
            </tr>
        </form>
    <?php endforeach; ?>
    </tbody>
</table>
