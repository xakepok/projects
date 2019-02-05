<?php
defined('_JEXEC') or die;
if (!empty($this->activities)) :
    ?>
    <ul class="list-group">
        <?php foreach ($this->activities as $field) :
            if (!$field['checked']) continue;
            ?>
            <li class="list-group-item"><?php echo $field['title']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>