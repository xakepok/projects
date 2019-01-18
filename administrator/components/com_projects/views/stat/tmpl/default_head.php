<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%">
        №
    </th>
    <th width="8%">
        <?php if ($this->itemID != 0) {
            echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_PAYMENT_CONTRACT_DESC', 'c.number', $listDirn, $listOrder);
        } else {
            echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_APPLICATION', 'application', $listDirn, $listOrder);
        }
        ?>
    </th>
    <?php if ($this->itemID != 0): ?>
    <th width="5%">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
    </th>
    <?php endif;?>
    <th>
        <?php if ($this->itemID != 0) {
            echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_PAYMENT_EXP_DESC', 'title_ru_short', $listDirn, $listOrder);
        } else {
            echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_TITLE', 'i.title_ru', $listDirn, $listOrder);
        }
        ?>
    </th>
    <th width="5%">
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT'); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_RUB_SHORT', 'price_rub', $listDirn, $listOrder); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_USD_SHORT', 'price_usd', $listDirn, $listOrder); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_EUR_SHORT', 'price_eur', $listDirn, $listOrder); ?>
    </th>
    <th width="3%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT', 'value', $listDirn, $listOrder); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_RUB', 'amount_rub', $listDirn, $listOrder); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_USD', 'amount_usd', $listDirn, $listOrder); ?>
    </th>
    <th width="7%">
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_ITEM_PRICE_EUR', 'amount_eur', $listDirn, $listOrder); ?>
    </th>
</tr>