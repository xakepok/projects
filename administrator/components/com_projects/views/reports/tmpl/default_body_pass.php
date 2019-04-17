<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items['contracts'] as $contractID => $item) :
    ?>
    <tr>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['number']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['stands']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['exhibitor']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['title_ru_full']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['manager']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['site']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['contacts']; ?>
        </td>
        <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
            <td class="small">
                <?php echo $item['squares'][$item_id]['value'] ?? 0; ?>
            </td>
        <?php endforeach;?>
    </tr>
<?php endforeach; ?>
    <tr>
        <td colspan="8" style="text-align: right; font-style: italic;">
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?>
        </td>
        <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
            <td class="small">
                <?php echo $this->items['sum'][$item_id] ?? 0; ?>
            </td>
        <?php endforeach;?>
    </tr>
