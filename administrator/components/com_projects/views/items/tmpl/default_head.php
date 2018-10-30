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
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TITLE'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_RUB'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_USD'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_EUR'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_FACTOR'); ?>
    </th>
    <th width="1%">
        <?php echo JHtml::_('grid.sort', 'ID', '`id`', $listDirn, $listOrder); ?>
    </th>
</tr>