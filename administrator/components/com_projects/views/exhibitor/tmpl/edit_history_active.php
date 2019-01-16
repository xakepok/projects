<?php
defined('_JEXEC') or die;
$return = base64_encode(JUri::base() . "index.php?option=com_projects&view=exhibitor&layout=edit&id={$this->item->id}");
if ($this->item->id != null) {
    $addUrl = JRoute::_("index.php?option=com_projects&amp;task=contract.add&amp;exhibitorID={$this->item->id}&amp;return={$return}");
    $addLink = JHtml::link($addUrl, JText::sprintf('COM_PROJECTS_ACTION_OPEN_CONTRACT'), array('style' => 'font-size: 2em;'));
}
?>
    <div class="center"><h4><?php echo JText::sprintf('COM_PROJECTS_BLANK_ACTIVE_PROJECTS'); ?></h4></div>
<?php if (!empty($this->history['process'])): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_PROJECT'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_MANAGER'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_HISTORY_STATUS'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_BLANK_TODOS'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->history['process'] as $item): ?>
            <tr>
                <td>
                    <?php echo $item['project']; ?>
                </td>
                <td>
                    <?php echo $item['manager']; ?>
                </td>
                <td>
                    <?php echo $item['status']; ?>
                </td>
                <td>
                    <?php echo $item['todos']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if (empty($this->history['process'])): ?>
    <?php echo JText::sprintf('COM_PROJECT_INFO_NO_ACTIVE_CONTRACTS');?>
<?php endif; ?>
<?php if ($this->item->id != null): ?>
    <div>
        <?php echo $addLink; ?>
    </div>
<?php endif; ?>