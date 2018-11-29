<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT_SUM', number_format($this->items['amount']['rub'], 2, '.', "'"), number_format($this->items['amount']['usd'], 2, '.', "'"), number_format($this->items['amount']['eur'], 2, '.', "'")); ?>