<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
foreach ($this->items as $item) :
    ?>
    <tr>
        <td class="small">
            <?php echo ++$ii; ?>
        </td>
        <td class="small">
            <?php echo $item['exhibitor']; ?>
        </td>
        <?php if (in_array('director_name', $this->fields)): ?>
            <td class="small">
                <?php echo $item['director_name']; ?>
            </td>
        <?php endif; ?>
        <?php if (in_array('director_post', $this->fields)): ?>
            <td class="small">
                <?php echo $item['director_post']; ?>
            </td>
        <?php endif; ?>
        <?php if (in_array('address_legal', $this->fields)): ?>
            <td class="small">
                <?php echo $item['address_legal']; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>