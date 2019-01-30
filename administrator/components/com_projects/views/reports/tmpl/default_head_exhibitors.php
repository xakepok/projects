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
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_EXP_TITLE_RU_FULL_DESC', 'e.title_ru_full', $listDirn, $listOrder); ?>
    </th>
    <?php if (in_array('director_name', $this->fields)): ?>
        <th>
            <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_EXP_CONTACT_DIRECTOR_NAME_DESC', 'cnt.director_name', $listDirn, $listOrder); ?>
        </th>
    <?php endif;?>
    <?php if (in_array('director_post', $this->fields)): ?>
        <th>
            <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_EXP_CONTACT_DIRECTOR_POST', 'cnt.director_post', $listDirn, $listOrder); ?>
        </th>
    <?php endif;?>
    <?php if (in_array('address_legal', $this->fields)): ?>
        <th>
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CONTACT_SPACER_LEGAL'); ?>
        </th>
    <?php endif;?>
</tr>