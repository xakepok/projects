<?php
defined('_JEXEC') or die;
if (JFactory::getApplication()->input->getInt('id', 0) == 0) echo JText::sprintf('COM_PROJECTS_MESSAGE_EDIT_PRICE_AFTER_SAVE');
$sum = 0;
?>
<fieldset class="adminform" id="cmptd">
    <table class="table table-striped" id="tblNeedsScrolling">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE_RU'); ?>
            </th>
            <th width="10%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEM'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_NUMBER'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_1'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_2'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_3'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_TWO'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DISCOUNT'); ?>
            </th>
            <th width="5%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_IS_MARKUP'); ?>
            </th>
            <th width="8%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SCORE_AMOUNT'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->price as $application => $sec) : ?>
            <tr>
                <td colspan="10" class="center"
                    style="font-weight: bold;"><?php echo ProjectsHelper::getApplication($application); ?></td>
            </tr>
            <?php foreach ($sec as $section => $arr) :
                $subsum = 0;
                ?>
                <tr>
                    <td colspan="10" class="center" style="font-weight: bold;"><?php echo $section; ?></td>
                </tr>
                <?php foreach ($arr as $i => $item) :
                if ($this->tip == 1 && $item['value'][1] == 0 && $item['is_sq']) continue;
                $sum += $item['sum'];
                $subsum += $item['sum'];
                ?>
                <tr id="item_<?php echo $item['id']; ?>" class="section_<?php echo $item['section_id']; ?>"
                    data-section="<?php echo $item['section_id']; ?>" data-app="<?php echo $application; ?>">
                    <td class="small">
                        <span data-section="<?php echo $section; ?>"
                              id="label_<?php echo $item['id']; ?>"><?php echo $item['title']; ?></span>
                    </td>
                    <td class="price_cost small">
                        <?php echo $item['cost']; ?>
                    </td>
                    <td class="price_cost">
                        <?php echo $item['stand']; ?>
                    </td>
                    <?php for ($j = 1; $j <= 3; $j++): ?>
                        <td>
                            <?php if (!$item['block'] && !$item['fixed'][$j]): ?>
                                <?php if (ProjectsHelper::canDo('projects.access.contracts.columns')) {
                                    if (($j == 2 || $j == 3) && $item['value'][$j] > 0) {
                                        $url = JRoute::_("index.php?option=com_projects&amp;task=ctritem.changeColumn&amp;id={$item['ctrItemID']}&amp;column=1&amp;return={$this->return}");
                                        echo JHtml::link($url, "&lt;");
                                    }
                                }?>
                                <input
                                        type="text"
                                        name="jform[price][<?php echo $item['id']; ?>][value]"
                                        id="price_<?php echo $item['id']; ?>"
                                        value="<?php echo ($this->tip != 0 && $item['sq']) ? $item['stands_count'] : $item['value'][$j]; ?>"
                                        data-cost="<?php echo $item['cost_clean']; ?>"
                                        class="input"
                                        placeholder=""
                                        autocomplete="off"
                                        style="width: 25px;"
                                        aria-invalid="false"/>&nbsp;
                            <?php endif; ?>
                            <?php if ($item['block'] || $item['fixed'][$j]): ?>
                                <?php if (!$item['fixed'][$j]): ?>
                                    <input
                                    type="hidden"
                                    name="jform[price][<?php echo $item['id']; ?>][value]"
                                    value="<?php echo ($this->tip != 0 && $item['sq']) ? $item['stands_count'] : $item['value'][$j]; ?>"
                                    />
                                <?php endif;?>
                                <?php if ($item['fixed'] && $item['ctrItemID'] != null) {
                                    if (ProjectsHelper::canDo('projects.access.contracts.columns')) echo JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=ctritem.edit&amp;id={$item['ctrItemID']}&amp;return={$this->return}"), $item['value'][$j] ?? 0);
                                    if (!ProjectsHelper::canDo('projects.access.contracts.columns')) echo $item['value'][$j] ?? 0;
                                } ?>
                                <?php if ($item['fixed'] && $item['ctrItemID'] == null) {
                                    if (ProjectsHelper::canDo('projects.access.contracts.columns')) echo JHtml::link(JRoute::_("index.php?option=com_projects&amp;task=ctritem.add&amp;contractID={$this->item->id}&amp;itemID={$item['id']}&amp;columnID={$j}&amp;return={$this->return}"), '0');
                                    if (!ProjectsHelper::canDo('projects.access.contracts.columns')) echo '0';
                                } ?>
                            <?php endif; ?>
                            <span><?php if ($item['value'][$j] != null && $item['value'][$j] != 0) echo $item['unit']; ?></span>
                        </td>
                    <?php endfor;?>
                    <td class="center">
                        <?php if ($item['isUnit2']): ?>
                            <?php if (!$item['is_sq']): ?>
                                <input
                                        type="text"
                                        name="jform[price][<?php echo $item['id']; ?>][value2]"
                                        id="value2_<?php echo $item['id']; ?>"
                                        value="<?php echo $item['value2']; ?>"
                                        class="input"
                                        placeholder=""
                                        autocomplete="off"
                                        onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                                        style="width: 50px;"
                                        aria-invalid="false"/>&nbsp;
                                <span><?php echo $item['unit2']; ?></span>
                            <?php endif; ?>
                            <?php if ($item['is_sq']): ?>
                                <input
                                        type="hidden"
                                        name="jform[price][<?php echo $item['id']; ?>][value2]"
                                        id="value2_<?php echo $item['id']; ?>"
                                        value="<?php echo $item['value2']; ?>"
                                        class="input"
                                        placeholder=""
                                        autocomplete="off"
                                        onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                                        style="width: 50px;"
                                        aria-invalid="false"/>&nbsp;
                                <span><?php echo $item['value2']; ?></span>
                                <span><?php echo $item['unit2']; ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php if ($item['is_factor']): ?>
                            <input
                                    type="number"
                                    name="jform[price][<?php echo $item['id']; ?>][factor]"
                                    id="factor_<?php echo $item['id']; ?>"
                                    value="<?php echo $item['factor'] ?? 0; ?>"
                                    min="0"
                                    max="100"
                                    placeholder="1.0"
                                    autocomplete="off"
                                    onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
                                    style="width: 40px;"
                                    aria-invalid="false"/>&nbsp;%
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php if ($item['is_markup']): ?>
                        <select
                                name="jform[price][<?php echo $item['id']; ?>][markup]"
                                id="markup_<?php echo $item['id']; ?>"
                                autocomplete="off"
                                style="width: 100px;"
                                onchange="getSum2(<?php echo $item['id']; ?>, '<?php echo $item['currency']; ?>')"
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
                    <td class="small">
                        <span class="amounts" id="sum_<?php echo $item['id']; ?>"
                              style="display: none;"><?php echo $item['sum']; ?></span>&nbsp
                        <span class="amounts"
                              id="sumV_<?php echo $item['id']; ?>"><?php echo number_format($item['sum'], 2, ',', ' '); ?></span>
                        <span id="currency_<?php echo $item['id']; ?>"><?php echo $item['currency']; ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>