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
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_FILTER_EXHIBITOR', 'exhibitor', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_EXP_TITLE_RU_FULL_DESC', 'e.title_ru_full', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CONTACT_SITE'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_BLANK_CONTRACTOR_CONTACTS'); ?>
    </th>
    <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
        <th>
            <?php echo $item_title; ?>
        </th>
    <?php endforeach;?>
</tr>