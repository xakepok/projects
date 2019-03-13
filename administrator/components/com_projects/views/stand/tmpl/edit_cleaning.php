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
            <th style="width: 10%">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_ITEM_PRICE_ITEMS_COUNT'); ?>
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
                <input
                        type="text"
                        name="jform[price][<?php echo $item['id']; ?>][value]"
                        value="<?php echo $item['value']; ?>"
                        class="input"
                        placeholder=""
                        autocomplete="off"
                        style="width: 50px;"
                        aria-invalid="false"/>&nbsp;
                <span><?php echo $item['unit']; ?></span>
            </td>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>