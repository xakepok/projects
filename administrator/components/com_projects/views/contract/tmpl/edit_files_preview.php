<?php
defined('_JEXEC') or die;
?>
<ul>
    <?php
    foreach ($this->item->files as $file) :
        ?>
        <li>
            <?php echo JHtml::link("/images/contracts/{$this->item->id}/{$file}", $file, array('target' => '_blank')); ?>
        </li>
    <?php
    endforeach; ?>
</ul>