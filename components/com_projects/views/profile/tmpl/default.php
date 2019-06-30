<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_projects/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_projects/style.css', array('version' => 'auto', 'relative' => true));
?>
<div><h5><?php echo $this->item['title'];?></h5></div>
<div>
    <form action="<?php echo ProjectsHelper::getActionUrl();?>">
        <div class="form-row">
            <div class="col">
                <label for="inn"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_INN');?></label>
                <input type="text" class="form-control" name="inn" id="inn" value="<?php echo $this->item['inn'];?>">
            </div>
            <div class="col">
                <label for="kpp"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_KPP');?></label>
                <input type="text" class="form-control" name="kpp" id="kpp" value="<?php echo $this->item['kpp'];?>">
            </div>
            <div class="col">
                <label for="bik"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_BIK');?></label>
                <input type="text" class="form-control" name="bik" id="bik" value="<?php echo $this->item['bik'];?>">
            </div>
        </div>
        <div class="form-group">
            <label for="rs"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_RS');?></label>
            <input type="text" class="form-control" name="rs" id="rs" value="<?php echo $this->item['rs'];?>">
        </div>
        <div class="form-group">
            <label for="ks"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_KS');?></label>
            <input type="text" class="form-control" name="ks" id="ks" value="<?php echo $this->item['ks'];?>">
        </div>
        <div class="form-group">
            <label for="bank"><?php echo JText::sprintf('COM_PROJECTS_PROFILE_BANK');?></label>
            <input type="text" class="form-control" name="bank" id="bank" value="<?php echo $this->item['bank'];?>">
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="<?php echo JText::sprintf('JTOOLBAR_APPLY');?>">
        </div>
    </form>
</div>
