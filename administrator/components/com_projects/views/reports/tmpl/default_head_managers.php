<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$cnts = array();
foreach ($this->items as $manager => $statuses) {
    foreach ($statuses as $status => $cnt) {
        if (!in_array($status, $cnts)) $cnts[] = $status;
    }
}
?>
<tr>
    <th width="1%">
        №
    </th>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TMPL_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <?php foreach ($cnts as $status) :?>
    <th>
        <?php echo ProjectsHelper::getExpStatus($status);?>
    </th>
    <?php endforeach;?>
</tr>