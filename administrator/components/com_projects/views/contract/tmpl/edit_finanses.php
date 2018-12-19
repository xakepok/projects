<?php
defined('_JEXEC') or die;
?>
<table class="table table-striped">
    <?php foreach ($this->item->finanses['scores'] as $finans): ?>
    <tr>
        <th>
            <?php
            echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_NUM_FROM_WITH_AMOUNT', $finans->number, $finans->amount, $this->item->currency, $finans->dat, $this->item->number, sprintf("%s %s", $this->item->amount, $this->item->currency), $this->exhibitor);
            echo ". ", ProjectsHelper::getScoreState($finans->state);
            ?>
        </th>
    </tr>
    <tr>
        <td>
            <table class="table table-striped">
                <tr>
                    <th>
                        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_PP'); ?>
                    </th>
                    <th>
                        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_DATE_DESC'); ?>
                    </th>
                    <th>
                        <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_AMOUNT_DESC'); ?>
                    </th>
                </tr>
                <?php foreach ($this->item->finanses['payments'][$finans->id] as $payment): ?>
                    <tr>
                        <td>
                            <?php echo $payment->pp;?>
                        </td>
                        <td>
                            <?php echo $payment->dat;?>
                        </td>
                        <td>
                            <?php echo sprintf("%s %s", $payment->amount, $this->item->currency);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </td>
        <?php endforeach; ?>
    </tr>
</table>
