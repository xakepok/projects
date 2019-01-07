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
    <?php
    $projectinactive = $this->state->get('filter.projectinactive');
    if (is_numeric($projectinactive)) :?>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_TITLE_NEW_CONTRACT');?>
        </th>
    <?php endif;?>
    <?php
    $projectactive = $this->state->get('filter.projectactive');
    if (is_numeric($projectactive)) :?>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_GO_FIND_CONTRACTS');?>
        </th>
    <?php endif;?>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CITY', '`city`', $listDirn, $listOrder); ?>
    </th>
</tr>