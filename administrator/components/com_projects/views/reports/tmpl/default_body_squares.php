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
            <?php echo $item['info']['dat']; ?>
        </td>
        <td class="small">
            <?php echo $item['info']['exhibitor']; ?>
        </td>
        <?php foreach ($this->items['items'] as $item_id => $item_title) :?>
            <td class="small">
                <?php echo $item['squares'][$item_id]['value'] ?? 0; ?>
            </td>
        <?php endforeach;?>
        <td class="small">
            <?php echo ($item['info']['currency'] == 'rub') ? ProjectsHelper::getCurrency((float) $item['info']['amount'], 'rub') : 0;?>
        </td>
        <td class="small">
            <?php echo ($item['info']['currency'] == 'usd') ? ProjectsHelper::getCurrency((float) $item['info']['amount'], 'usd') : 0;?>
        </td>
        <td class="small">
            <?php echo ($item['info']['currency'] == 'eur') ? ProjectsHelper::getCurrency((float) $item['info']['amount'], 'eur') : 0;?>
        </td>
    </tr>
<?php endforeach; ?>