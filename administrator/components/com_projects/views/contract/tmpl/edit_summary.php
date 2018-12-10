<?php
defined('_JEXEC') or die;
$currency = '';
$sum = 0;
?>
<fieldset class="adminform" style="border: 1px solid black">
    <table class="addPrice">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_SECTION'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT'); ?>
            </th>
        </tr>
        </thead>
        <tbody class="sumbody">
        <?php foreach ($this->price as $section => $arr) :
            $subsum = 0;
            ?>
            <?php foreach ($arr as $i => $item):
            if ($item['sum'] == 0) continue;
            $sum += $item['sum'];
            $currency = $item['currency'];
            ?>
            <tr id="summary_<?php echo $item['id']; ?>">
                <td>
                    <?php echo $item['title']; ?>
                </td>
                <td>
                    <?php echo $section; ?>
                </td>
                <td>
                    <span id="sumS_<?php echo $item['id']; ?>"><?php echo $item['sum']; ?></span>
                    <span id="currencyS_<?php echo $item['id']; ?>"><?php echo $currency; ?></span>
                </td>
            </tr>
        <?php
        endforeach;
        endforeach;
        ?>
        </tbody>
        <tfoor>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM'); ?>:&nbsp;
                    <span
                            id="sum_amountS"><?php echo $sum; ?></span>
                    <?php echo $currency;?>
                </td>
            </tr>
        </tfoor>
    </table>
</fieldset>