<?php
defined('_JEXEC') or die;
?>
    <div class="center"><h4><?php echo JText::sprintf('COM_PROJECTS_BLANK_CHILDREN_COMPANIES'); ?></h4></div>
<?php if (!empty($this->children)): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_COMPANY_TITLE'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CARD'); ?>
            </th>
            <th>
                <?php echo JText::sprintf('COM_PROJECTS_BLANK_EXHIBITOR_HISTORY'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->children as $item): ?>
            <tr>
                <td>
                    <?php echo $item['link']; ?>
                </td>
                <td>
                    <a href="#modalCard" data-toggle="modal"
                       onclick="showCard(<?php echo $item['id']; ?>); return true;"><?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CARD'); ?></a>
                </td>
                <td>
                    <?php echo $item['contracts']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php echo $this->loadTemplate('card'); ?>