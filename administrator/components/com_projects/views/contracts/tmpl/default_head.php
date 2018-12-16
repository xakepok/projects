<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
        <input type="checkbox" name="checkall-toggle" value=""
               title="<?php echo JText::sprintf('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
    </th>
    <th>
        №
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_NUMBER_SHORT', 'number', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_SHORT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_ACTION_GO'); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_PROJECT', 'project', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_EXPONENT', 'title_ru_short', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_ACTIVE_TODOS', 'plan', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_DATE_DOG', 'c.dat', $listDirn, $listOrder); ?>
    </th>
    <?php if (ProjectsHelper::canDo('core.general')): ?>
        <th>
            <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_MANAGER', 'manager', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STATUS'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_PAYMENT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DEBT'); ?>
    </th>
</tr>