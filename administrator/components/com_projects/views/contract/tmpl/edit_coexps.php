<?php
defined('_JEXEC') or die;
$return = base64_encode("index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
if (!empty($this->item->coExps)):
    ?>
    <table class="table table-striped">
        <caption
                style="font-weight: bold;"><?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_CHILDREN'); ?></caption>
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_PAYMENT_COMPANY'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_SECTION_EVENT_CONTRACT'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_SHORT'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->item->coExps as $coExp): ?>
            <tr>
                <td>
                    <?php echo $coExp['exhibitor']; ?>
                </td>
                <td>
                    <?php echo $coExp['contract']; ?>
                </td>
                <td>
                    <?php echo $coExp['stands']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="font-style: italic;">
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_CONTRACT_STAND_IS_DELEGATED_HINT');?>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>