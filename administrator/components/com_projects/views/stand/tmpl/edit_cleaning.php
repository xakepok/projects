<?php
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_TITLE_RU'); ?>
            </th>
            <th style="width: 7%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_1'); ?>
            </th>
            <th style="width: 7%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_2'); ?>
            </th>
            <th style="width: 7%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT_SHORT_3'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->price['cleaning'] as $item) : ?>
        <tr>
            <td>
                <?php echo $item['title']; ?>
            </td>
            <td>
                <?php if ($this->active_column != 1): ?>
                    <span><?php echo $item['value'][1]; ?></span>
                <?php endif;?>
                <?php if ($this->active_column == 1): ?>
                    <input
                            type="text"
                            name="jform[price][<?php echo $item['id']; ?>]"
                            value="<?php echo $item['value'][1]; ?>"
                            class="input"
                            placeholder=""
                            autocomplete="off"
                            style="width: 50px;"
                            aria-invalid="false"
                    />
                <?php endif;?>
            </td>
            <td>
                <?php if ($this->active_column != 2): ?>
                    <span><?php echo $item['value'][2]; ?></span>
                <?php endif;?>
                <?php if ($this->active_column == 2): ?>
                    <input
                            type="text"
                            name="jform[price][<?php echo $item['id']; ?>]"
                            value="<?php echo $item['value'][2]; ?>"
                            class="input"
                            placeholder=""
                            autocomplete="off"
                            style="width: 50px;"
                            aria-invalid="false"
                    />
                <?php endif;?>
            </td>
            <td>
                <?php if ($this->active_column != 3): ?>
                    <span><?php echo $item['value'][3]; ?></span>
                <?php endif;?>
                <?php if ($this->active_column == 3): ?>
                    <input
                            type="text"
                            name="jform[price][<?php echo $item['id']; ?>]"
                            value="<?php echo $item['value'][3]; ?>"
                            class="input"
                            placeholder=""
                            autocomplete="off"
                            style="width: 50px;"
                            aria-invalid="false"
                    />
                <?php endif;?>
            </td>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>