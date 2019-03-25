<?php
defined('_JEXEC') or die;
$url = "index.php?option=com_projects&amp;task=todos.exportxls";
$url = JRoute::_($url);
echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_EXPORT_XLS'));
echo " / ";
$url = "index.php?option=com_projects&amp;view=todos&amp;format=raw";
$url = JRoute::_($url);
echo JHtml::link($url, JText::sprintf('COM_PROJECTS_ACTION_PRINT'));

