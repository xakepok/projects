<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $i => $item) :?>
    <tr>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo ++$ii; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['dat_open']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['dat']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['contract']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['project']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['exp']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['task']; ?>
        </td>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php if ($item['state'] != '0') echo $item['result']; ?>
        </td>
        <?php if ($this->isAdmin): ?>
            <td style="border: 1px dotted gray; font-size: 0.8em;">
                <?php echo $item['manager']; ?>
            </td>
        <?php endif; ?>
        <td style="border: 1px dotted gray; font-size: 0.8em;">
            <?php echo $item['state_text']; ?>
        </td>
    </tr>
<?php endforeach; ?>