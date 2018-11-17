<?php
defined('_JEXEC') or die;
if (JFactory::getApplication()->input->getInt('id', 0) == 0) echo JText::sprintf('COM_PROJECTS_MESSAGE_EDIT_PRICE_AFTER_SAVE');
?>
<fieldset class="adminform">
    <table>
        <thead>
            <tr>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE_RU');?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEM');?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT');?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_TWO');?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_DISCOUNT');?>
                </th>
                <th>
                    <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_IS_MARKUP');?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($this->price as $item) : ?>
            <tr>
                <td>
                    <div class="control-label">
                        <label for="price_<?php echo $item['id']; ?>" class="hasPopover" title="<?php echo $item['title']; ?>"
                               data-content="<?php echo $item['title']; ?>">
                            <?php echo $item['title']; ?>
                        </label>
                    </div>
                </td>
                <td>
                    <div class="span2" style="text-align: right; vertical-align: bottom;"><?php echo $item['cost']; ?></div>
                </td>
                <td>
                    <input
                            type="text"
                            name="jform[price][<?php echo $item['id']; ?>][value]"
                            id="price_<?php echo $item['id']; ?>"
                            value="<?php echo $item['value']; ?>"
                            class="input"
                            placeholder=""
                            autocomplete="off"
                        <?php if ($item['fixed']) echo "disabled "; ?>
                            style="width: 50px;"
                            aria-invalid="false"/>&nbsp;
                    <span><?php echo $item['unit']; ?></span>
                </td>
                <td>
                    <?php if ($item['isUnit2']): ?>
                        <input
                                type="text"
                                name="jform[price][<?php echo $item['id']; ?>][value2]"
                                id="value2_<?php echo $item['id']; ?>"
                                value="<?php echo $item['value2']; ?>"
                                class="input"
                                placeholder=""
                                autocomplete="off"
                            <?php if ($item['fixed']) echo "disabled "; ?>
                                style="width: 50px;"
                                aria-invalid="false"/>&nbsp;
                        <span><?php echo $item['unit2']; ?></span>
                    <?php endif; ?>
                </td>
                <td>
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
                        <?php if ($item['fixed']) echo "disabled "; ?>
                            style="width: 50px;"
                            aria-invalid="false"/>&nbsp;%
                </td>
                <td>
                    <?php if ($item['is_markup']): ?>
                        <select
                                name="jform[price][<?php echo $item['id']; ?>][markup]"
                                id="markup_<?php echo $item['id']; ?>"
                                class="input"
                                autocomplete="off"
                            <?php if ($item['fixed']) echo "disabled "; ?>
                                aria-invalid="false">
                            <option value="0" <?php if ($item['markup'] == 0 || $item['markup'] == null) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_0');?></option>
                            <option value="10" <?php if (round($item['markup']) == 10) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_10');?>%</option>
                            <option value="15" <?php if (round($item['markup']) == 15) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_15');?>%</option>
                            <option value="20" <?php if (round($item['markup']) == 20) echo 'selected'; ?>><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_MARKUP_20');?>%</option>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>