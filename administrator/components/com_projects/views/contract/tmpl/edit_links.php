<?php
defined('_JEXEC') or die;
$links = array();
if ($this->item->id != null) {
    $return = base64_encode(JUri::base() . "index.php?option=com_projects&view=contract&layout=edit&id={$this->item->id}");
    $exhibitorUrl = JRoute::_("index.php?option=com_projects&amp;task=exhibitor.edit&amp;id={$this->item->expID}&amp;return={$return}");
    $links[] = JHtml::link($exhibitorUrl, JText::sprintf('COM_PROJECTS_GO_EXHIBITOR'));
    $todoUrl = JRoute::_("index.php?option=com_projects&amp;task=todo.add&amp;contractID={$this->item->id}&amp;return={$return}");
    $links[] = JHtml::link($todoUrl, JText::sprintf('COM_PROJECTS_GO_CREATE_TODO'));
    $standUrl = JRoute::_("index.php?option=com_projects&amp;task=stand.add&amp;contractID={$this->item->id}&amp;return={$return}");
    $links[] = JHtml::link($standUrl, JText::sprintf('COM_PROJECTS_GO_CREATE_STAND'));
    $projectUrl = JRoute::_("index.php?option=com_projects&amp;task=project.edit&amp;id={$this->item->prjID}&amp;return={$return}");
    if (ProjectsHelper::canDo('core.general')) $links[] = JHtml::link($projectUrl, JText::sprintf('COM_PROJECTS_GO_PROJECT'));
    $scoreUrl = JRoute::_("index.php?option=com_projects&amp;task=score.add&amp;contractID={$this->item->id}&amp;return={$return}");
    if (ProjectsHelper::canDo('core.general') || (ProjectsHelper::canDo('core.accountant'))) $links[] = JHtml::link($scoreUrl, JText::sprintf('COM_PROJECTS_GO_CREATE_SCORE'));
}
?>
<h4><?php if ($this->item->id != null) echo JText::sprintf('COM_PROJECTS_GO_TITLE');?></h4>
<ul>
    <?php foreach ($links as $link): ?>
        <li>
            <?php echo $link;?>
        </li>
    <?php endforeach;?>
</ul>