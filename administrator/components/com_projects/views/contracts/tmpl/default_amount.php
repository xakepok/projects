<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_AMOUNT_SUM', $this->items['amount']['rub'], $this->items['amount']['usd'], $this->items['amount']['eur']);?>