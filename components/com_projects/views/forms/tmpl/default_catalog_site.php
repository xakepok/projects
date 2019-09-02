<?php
defined('_JEXEC') or die;
?>

<div><h5><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TITLE');?></h5></div>
<div>
    <form action="#">
        <div class="form-group row">
            <label for="title" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME');?></label>
            <div class="col-md-10">
                <input type="text" name="title" id="title" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="diploma" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_DIPLOMA');?></label>
            <div class="col-md-10">
                <input type="text" name="diploma" id="diploma" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_ru" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_RU');?></label>
            <div class="col-md-10">
                <input type="text" name="title_ru" id="title_ru" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_en" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_EN');?></label>
            <div class="col-md-10">
                <input type="text" name="title_en" id="title_en" class="form-control">
            </div>
        </div>
    </form>
</div>