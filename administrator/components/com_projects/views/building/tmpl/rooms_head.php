<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_NUMBER_TITLE', 'stand', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ROOM_CATEGORY', 'nc.title_ru', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ROOM_HOTEL', 'h.title_ru', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_ROOM_ARRIVAL', 's.arrival', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_ROOM_DEPARTMENT', 's.department', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TODO_EXP', 'title_ru_short', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_PAYMENT_CONTRACT_DESC', 'contract', $listDirn, $listOrder); ?>
    </th>
</tr>