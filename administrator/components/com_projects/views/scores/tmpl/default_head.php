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
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_SCORE_NUMBER', 'number', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_CONTRACT_NUMBER', 'number_contract', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_SCORE_DATE', 's.dat', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TODO_PROJECT', 'project', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TODO_EXP', 'title_ru_short', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php
        $currency = $this->state->get('filter.currency');
        echo (!empty($currency)) ? JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_SCORE_AMOUNT', 'amount', $listDirn, $listOrder) : JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT');
        ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_PAYMENT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_DEBT'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_MENU_PAYMENTS'); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_STATE'); ?>
    </th>
</tr>