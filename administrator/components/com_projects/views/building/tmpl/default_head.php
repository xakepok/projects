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
        <?php echo JHtml::_('grid.sort', '№', 'stand', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_HEAD_SQUARE', 'sq', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_STAND_STATUS', 's.status', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TODO_EXP', 'title_ru_short', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_STATUS_DOG', 'exp_status', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_STAND_TYPE', 's.tip', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_STAND_SCHEME', 'scheme', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_PAYMENT_CONTRACT_DESC', 'contract', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_STAND_FREEZE', 'freeze', $listDirn, $listOrder); ?>
    </th>
</tr>