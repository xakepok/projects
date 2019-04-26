<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <?php echo JHtml::_('grid.checkall'); ?>
    </th>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_TITLE', 'title', $listDirn, $listOrder); ?>
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
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_MANAGER_ADD', 'manager', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CITY', 'city', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'ID', 'e.id', $listDirn, $listOrder); ?>
    </th>
</tr>