<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::sprintf('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th width="5%">
        <?php echo JHtml::_('grid.sort', 'JSTATUS', '`state`', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PROJECT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_EXPONENT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_TODOS'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DATE'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MANAGER'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_PROJECT_GROUP'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DEBT'); ?>
    </th>
    <th width="1%">
        <?php echo JHtml::_('grid.sort', 'ID', '`id`', $listDirn, $listOrder); ?>
    </th>
</tr>