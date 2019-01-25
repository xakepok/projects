<?php
defined('_JEXEC') or die;
$currency = '';
$sum = 0;
?>
<fieldset class="adminform">
    <table class="addPrice table table-striped">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE'); ?>
            </th>
            <th style="width: 10%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
            </th>
            <th style="width: 3%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT'); ?>
            </th>
            <th style="width: 8%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEM'); ?>
            </th>
            <th style="width: 3%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DISCOUNT'); ?>
            </th>
            <th style="width: 3%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP'); ?>
            </th>
            <th style="width: 8%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT'); ?>
            </th>
        </tr>
        </thead>
        <tbody class="sumbody">
        <?php foreach ($this->price as $application => $sec) :
            $subsum = 0;
            ?>
            <tr>
                <td colspan="7" class="center"
                    style="font-weight: bold;"><?php echo ProjectsHelper::getApplication($application); ?></td>
            </tr>
            <?php foreach ($sec as $section => $arr) :
                foreach ($arr as $i => $item):
                    $dsp = ($item['sum'] == 0 && $item['value'] == 0) ? 'hidden' : '';
                    $sum += (float) $item['sum'];
                    $currency = $item['currency'];
                    $subsum += (float) $item['sum']; ?>
                    <tr id="summary_<?php echo $item['id']; ?>" class="app_<?php echo $application;?> <?php echo $dsp; ?>" data-app="<?php echo $application;?>">
                        <td>
                            <?php echo $item['title']; ?>
                        </td>
                        <td class="price_cost">
                            <?php echo $item['stand']; ?>
                        </td>
                        <td>
                            <span id="sum_cnt_<?php echo $item['id']; ?>"><?php echo $item['value']; ?></span>&nbsp;<?php echo $item['unit']; ?>
                        </td>
                        <td>
                            <?php echo $item['cost']; ?>
                        </td>
                        <td>
                            <span id="sum_factor_<?php echo $item['id']; ?>"><?php echo $item['factor']; ?></span>%
                        </td>
                        <td>
                            <span id="sum_markup_<?php echo $item['id']; ?>"><?php echo $item['markup']; ?></span>%
                        </td>
                        <td>
                            <span id="sumS_<?php echo $item['id']; ?>" style="display: none;"><?php echo $item['sum']; ?></span>
                            <span id="sumSV_<?php echo $item['id']; ?>"><?php echo $item['sum_showed']; ?></span>
                            <span id="currencyS_<?php echo $item['id']; ?>"><?php echo $currency; ?></span>
                        </td>
                    </tr>
                <?php
                endforeach;
            endforeach; ?>
            <tr>
                <td colspan="6"
                    style="text-align: right; font-weight: bold;"><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUBSUM'); ?></td>
                <td>
                    <span id="subsumapp_<?php echo $application;?>" style="display: none;"><?php echo $subsum; ?></span>
                    <span id="subsumappV_<?php echo $application;?>"><?php echo number_format($subsum, 2, ',', ' '); ?></span>
                    <?php echo $currency; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoor>
            <tr>
                <td colspan="7" style="text-align: right; font-weight: bold;">
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM'); ?>:&nbsp;
                    <span id="sum_amountS" style="display: none;"><?php echo $sum;?></span>
                    <span id="sum_amountSV"><?php echo number_format($sum, 2, ',', ' ');?></span>
                    <?php echo $currency; ?>
                </td>
            </tr>
        </tfoor>
    </table>
</fieldset>