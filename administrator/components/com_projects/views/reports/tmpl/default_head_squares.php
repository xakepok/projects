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
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_NUMBER_SHORT', 'c.number', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_SHORT'); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_DATE', 'c.dat', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_FILTER_EXHIBITOR', 'exhibitor', $listDirn, $listOrder); ?>
    </th>
    <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
        <th>
            <?php echo $item_title; ?>
        </th>
    <?php endforeach;?>
    <th style="width: 100px;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT_RUB'); ?>
    </th>
    <th style="width: 70px;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT_USD'); ?>
    </th>
    <th style="width: 70px;">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT_EUR'); ?>
    </th>
</tr>