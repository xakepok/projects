<?php
defined('_JEXEC') or die;

?>
<div class="container-fluid">

    <div class="row-fluid">

        <div class="control-group span6">
            <div class="controls">
                <?php echo JLayoutHelper::render('joomla.html.batch.item', array('extension' => 'com_projects')); ?>
            </div>
            <div class="controls">
                <?php echo JLayoutHelper::render('position', array()); ?>
            </div>
        </div>

        <div class="control-group span6">
            <div class="controls">
                <?php echo JLayoutHelper::render('joomla.html.batch.language', array()); ?>
            </div>
            <div class="controls">
                <?php echo JLayoutHelper::render('joomla.html.batch.access', array()); ?>
            </div>
            <div class="controls">
                <?php echo JLayoutHelper::render('joomla.html.batch.tag', array()); ?>
            </div>
        </div>

    </div>

</div>