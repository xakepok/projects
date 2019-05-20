<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
$colspan = (ProjectsHelper::canDo('core.general')) ? 11 + count($this->advanced_items) : 10 + count($this->advanced_items);
$last_pavilion = '';
foreach ($this->items['stands'] as $item) :?>
    <?php
    if ($last_pavilion != '' && $last_pavilion != $item['pavilion']): ?>
    <tr>
        <th colspan="2"><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?></th>
        <td colspan="<?php echo (ProjectsHelper::canDo('core.general')) ? 9 + count($this->advanced_items) : 8 + count($this->advanced_items);?>">
            <?php echo $item['square'][$last_pavilion].' '.JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM');?>
        </td>
    </tr>
    <?php endif;
    if ($last_pavilion != $item['pavilion']): ?>
    <tr>
        <th colspan="<?php echo $colspan;?>" class="center"><?php echo ProjectsHelper::getStandPavilion($item['pavilion']);?></th>
    </tr>
    <?php endif; ?>
    <tr class="row0<?php if ($item['expired']) echo ' expired'; ?>">
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['stand']; ?>
        </td>
        <td>
            <?php echo $item['sq']; ?>
        </td>
        <td>
            <?php echo $item['status']; ?>
        </td>
        <td>
            <?php echo $item['exhibitor']; ?>
        </td>
        <td>
            <?php echo $item['manager']; ?>
        </td>
        <td>
            <?php echo $item['exp_status']; ?>
        </td>
        <td>
            <?php echo $item['tip']; ?>
        </td>
        <?php if (ProjectsHelper::canDo('core.general')) : ?>
            <td>
                <?php echo $item['scheme']; ?>
            </td>
        <?php endif; ?>
        <td>
            <?php echo $item['contract']; ?>
        </td>
        <td>
            <?php echo $item['freeze']; ?>
        </td>
        <?php foreach ($this->advanced_items as $advanced_item) :?>
            <td>
                <?php echo $this->items['advanced'][$advanced_item][$item['standID']] ?? 0;?>
            </td>
        <?php endforeach;?>
    </tr>
    <?php $last_pavilion = $item['pavilion'];?>
<?php endforeach; ?>
<tr>
    <th colspan="2"><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_SUM');?></th>
    <td colspan="<?php echo (ProjectsHelper::canDo('core.general')) ? 9 : 8;?>">
        <?php echo $item['square'][$last_pavilion].' '.JText::sprintf('COM_PROJECTS_HEAD_ITEM_UNIT_SQM');?>
    </td>
</tr>
