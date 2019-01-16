<?php
defined('_JEXEC') or die;
if (!empty($this->activities)) :
    ?>
    <ul>
        <?php foreach ($this->activities as $field) :
            if (!$field['checked']) continue;
            ?>
            <li><?php echo $field['title']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>