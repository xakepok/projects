<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_DATE', 'a.dat', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_SECTION_EVENT', 'section', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ACTION', 'action', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo 'ID'; ?>
    </th>
</tr>