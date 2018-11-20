<?php
defined('_JEXEC') or die;
$currency = '';
$sum = 0;
?>
<fieldset class="adminform" style="border: 1px solid black">
    <table width="100%">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE_RU'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->price as $item) :
            //if ($item['value'] == null) continue;
            $currency = $item['currency'];
            $sum += $item['sum'];
            ?>
            <tr>
                <td style="width: 80%">
                    <div class="control-label">
                        <label for="price_<?php echo $item['id']; ?>" class="hasPopover"
                               title="<?php echo $item['title']; ?>"
                               data-content="<?php echo $item['title']; ?>">
                            <?php echo $item['title']; ?>
                        </label>
                    </div>
                </td>
                <td style="width: 20%">
                    <span class="amounts" id="sum_<?php echo $item['id']; ?>"><?php echo $item['sum'];?></span>&nbsp;<span
                            id="currency_<?php echo $item['id']; ?>"><?php echo $item['currency'];?></span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" style="text-align: right; font-weight: bold;">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM'); ?>:&nbsp;<span id="sum_amount"><?php echo $sum, " ", $currency;?></span>
            </td>
        </tr>
        </tfoot>
    </table>
</fieldset>