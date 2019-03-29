<?php
defined('_JEXEC') or die;
?>
<ul>
    <?php
    foreach ($this->item->file_list as $file) :
        ?>
        <li>
            <?php echo JHtml::link("/images/contracts/{$this->item->id}/{$file['path']}", sprintf("%s: %s", $file['date'] , $file['path']), array('target' => '_blank')); ?>
        </li>
    <?php
    endforeach; ?>
</ul>