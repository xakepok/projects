<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th style="width: 1%;" class="hidden-phone">
        <?php echo JHtml::_('grid.checkall'); ?>
    </th>
    <th>
        №
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_NUMBER_SHORT', 'num', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 4%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_DATE_DOG', 'dat', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 4%;">
        <?php
        if (!ProjectsHelper::canDo('projects.access.hotels.standart')) $head = 'COM_PROJECTS_HEAD_CONTRACT_STAND_SHORT';
        if (ProjectsHelper::canDo('projects.access.hotels.standart')) $head = 'COM_PROJECTS_HEAD_CONTRACT_STANDS_ROOMS';
        if (ProjectsHelper::canDo('projects.access.hotels.full') && ProjectsHelper::canDo('projects.access.contracts.full')) $head = 'COM_PROJECTS_HEAD_CONTRACT_STANDS_ROOMS_SHORT';
        echo JText::sprintf($head);
        ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_ACTION_GO'); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_PROJECT', 'project', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_EXPONENT', 'exhibitor', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_COEXP_BY', 'parent', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_ACTIVE_TODOS', 'todos', $listDirn, $listOrder); ?>
    </th>
    <?php if (ProjectsHelper::canDo('projects.access.contracts.full')): ?>
        <th>
            <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_MANAGER', 'manager', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_STATUS', 'status_weight', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_DOC_STATUS_SHORT', 'doc_status', $listDirn, $listOrder); ?>
    </th>
    <th style="width: 9%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_AMOUNT', "sort_amount, amount", $listDirn, $listOrder); ?>
    </th>
    <th style="width: 9%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_SCORE_PAYMENT', "sort_amount, payments", $listDirn, $listOrder); ?>
    </th>
    <th style="width: 9%;">
        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECTS_HEAD_CONTRACT_DEBT', "sort_amount, debt", $listDirn, $listOrder); ?>
    </th>
</tr>