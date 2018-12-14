<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::sprintf('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TITLE', '`title_ru_short`', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CITY', '`city`', $listDirn, $listOrder); ?>
    </th>
</tr>