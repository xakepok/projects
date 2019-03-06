<?php
defined('_JEXEC') or die;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$dates = array();
$next_week = (int) date("W") + 1;
foreach ($this->items['current'] as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!in_array($dat, $dates) && date("W", strtotime($dat)) == $next_week) {
            $dates[] = $dat;
        }
    endforeach;
endforeach;
sort($dates);
setlocale(LC_TIME, "ru_RU.UTF-8");
?>
<tr>
    <th>
        <?php echo JHtml::_('grid.sort', 'COM_PROJECTS_HEAD_TMPL_MANAGER', 'manager', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_STATE_EXPIRES');?>
    </th>
    <?php foreach ($dates as $date) :?>
        <th>
            <?php echo strftime("%d.%m.%Y (%a)", strtotime($date)); ?>
        </th>
    <?php endforeach; ?>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODOS_FUTURE');?>
    </th>
    <th>
        <?php echo JText::sprintf('COM_PROJECTS_HEAD_TODO_COUNT_ON_WEEK');?>
    </th>
</tr>
