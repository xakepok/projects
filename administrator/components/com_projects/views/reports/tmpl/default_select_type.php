<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$types = ProjectsHelper::getReportTypes();
$links = array();
foreach ($types as $type => $title)
{
    $links[] = JHtml::link(JRoute::_("index.php?option=com_projects&amp;view=reports&amp;type={$type}"), $title);
}
?>
<ul>
    <?php foreach ($links as $link) :?>
        <li><?php echo $link;?></li>
    <?php endforeach; ?>
</ul>
