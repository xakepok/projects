<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = JFactory::getApplication()->input->getInt('limitstart', 0);
$managers = array();
$dates = array();
$futures = array();
$next_week = (int) strftime("%W") + 1; //Порядковый номер следующей недели
$todos_on_next_week = array(); //Количество заданий менеджеров на текущей неделе
foreach ($this->items['current'] as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!in_array($manager, $managers)) $managers[] = $manager;
        if (!in_array($dat, $dates) && strftime("%W", strtotime($dat)) == $next_week) {
            $dates[] = $dat;
            if (!isset($todos_on_next_week[$manager])) {
                $todos_on_next_week[$manager] = 0;
            }
        }
    endforeach;
endforeach;
sort($dates);
foreach ($this->items['future'] as $dat => $arr) :
    foreach ($arr as $manager => $cnt) :
        if (!isset($futures[$manager])) {
            $futures[$manager] = 0;
        }
        $futures[$manager] += $cnt;
    endforeach;
endforeach;
$projectID = $this->state->get('filter.project');
if (empty($projectID)) $projectID = ProjectsHelper::getActiveProject();
if (!is_numeric($projectID)) $projectID = 0;
$expires = ProjectsHelper::getExpiredTodosByManager($projectID);
foreach ($managers as $manager) :
    ?>
<tr>
    <td>
        <?php echo $manager;?>
    </td>
    <td>
        <?php echo $expires[$manager] ?? 0;?>
    </td>
    <?php
    foreach ($dates as $date) :?>
        <td>
            <?php echo $this->items['current'][$date][$manager] ?? 0;
            $todos_on_next_week[$manager] += $this->items['current'][$date][$manager];
            ?>
        </td>
    <?php endforeach; ?>
    <td>
        <?php echo $futures[$manager] ?? 0;?>
    </td>
    <td>
        <strong><?php echo (((int) $expires[$manager] ?? 0) + ((int) $todos_on_next_week[$manager] ?? 0));?></strong>
    </td>
</tr>
<?php
endforeach;