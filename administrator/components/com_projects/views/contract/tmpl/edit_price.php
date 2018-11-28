<?php
defined('_JEXEC') or die;
if (JFactory::getApplication()->input->getInt('id', 0) == 0) echo JText::sprintf('COM_PROJECTS_MESSAGE_EDIT_PRICE_AFTER_SAVE');
$sum = 0;
?>
<fieldset class="adminform">
    <table class="addPrice">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE_RU'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEM'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_TWO'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DISCOUNT'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_IS_MARKUP'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->price as $section => $arr) :
            $subsum = 0;
            ?>
            <tr>
                <td colspan="7" class="center" style="font-weight: bold;"><?php echo $section; ?></td>
            </tr>
            <?php foreach ($arr as $i => $item) :
            $sum += $item['sum'];
            $subsum += $item['sum'];
            ?>
            <tr id="item_<?php echo $item['id']; ?>" class="section_<?php echo $item['section_id']; ?>" data-section="<?php echo $item['section_id']; ?>">
                <td>
                    <span id="label_<?php echo $item['id']; ?>"><?php echo $item['title']; ?></span>
                </td>
                <td class="price_cost">
                    <?php echo $item['cost']; ?>
                </td>
                <td>
                    <input
                            type="text"
                            name="jform[price][<?php echo $item['id']; ?>][value]"
                            id="price_<?php echo $item['id']; ?>"
                            value="<?php echo $item['value']; ?>"
                            data-cost="<?php echo $item['cost_clean']; ?>"
                            class="input"
                            placeholder=""
                            autocomplete="off"
                            onkeyup="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                        <?php if ($item['fixed']) echo "disabled "; ?>
                            style="width: 50px;"
                            aria-invalid="false"/>&nbsp;
                    <span><?php echo $item['unit']; ?></span>
                </td>
                <td class="center">
                    <?php if ($item['isUnit2']): ?>
                        <input
                                type="text"
                                name="jform[price][<?php echo $item['id']; ?>][value2]"
                                id="value2_<?php echo $item['id']; ?>"
                                value="<?php echo $item['value2']; ?>"
                                class="input"
                                placeholder=""
                                autocomplete="off"
                                onkeyup="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                                onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                            <?php if ($item['fixed']) echo "disabled "; ?>
                                style="width: 50px;"
                                aria-invalid="false"/>&nbsp;
                        <span><?php echo $item['unit2']; ?></span>
                    <?php endif; ?>
                </td>
                <td class="center">
                    <?php if ($item['isUnit2']): ?>
                        <input
                                type="number"
                                name="jform[price][<?php echo $item['id']; ?>][factor]"
                                id="factor_<?php echo $item['id']; ?>"
                                value="<?php echo $item['factor'] ?? 0; ?>"
                                min="0"
                                max="100"
                                class="input"
                                placeholder="1.0"
                                autocomplete="off"
                                onkeyup="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                            <?php if ($item['fixed']) echo "disabled "; ?>
                                style="width: 50px;"
                                aria-invalid="false"/>&nbsp;%
                    <?php endif; ?>
                </td>
                <td class="center">
                    <?php if ($item['is_markup']): ?>
                    <select
                            name="jform[price][<?php echo $item['id']; ?>][markup]"
                            id="markup_<?php echo $item['id']; ?>"
                            class="input"
                            autocomplete="off"
                            onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                        <?php if ($item['fixed']) echo "disabled "; ?>
                            aria-invalid="false">
                        <option value="0" <?php if ($item['markup'] == 0 || $item['markup'] == null) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_0'); ?></option>
                        <option value="10" <?php if (round($item['markup']) == 10) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_10'); ?>
                            %
                        </option>
                        <option value="15" <?php if (round($item['markup']) == 15) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_15'); ?>
                            %
                        </option>
                        <option value="20" <?php if (round($item['markup']) == 20) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_20'); ?>
                            %
                        </option>
                        <?php endif; ?>
                </td>
                <td style="width: 20%">
                    <span class="amounts"
                          id="sum_<?php echo $item['id']; ?>"><?php echo $item['sum']; ?></span>&nbsp;<span
                            id="currency_<?php echo $item['id']; ?>"><?php echo $item['currency']; ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
            <tr>
                <td colspan="7" style="text-align: right">
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUBSUM'); ?>: <span
                            id="subsum_<?php echo $item['section_id']; ?>"><?php echo $subsum; ?></span>
                    <?php echo $item['currency']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="7" style="text-align: right; font-weight: bold;">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM'); ?>:&nbsp;<span
                        id="sum_amount"><?php echo number_format($sum, 2, '.', " "), " ", $item['currency']; ?></span>
            </td>
        </tr>
        </tfoot>
    </table>
</fieldset>