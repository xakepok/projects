<?php
defined('_JEXEC') or die;
?>
<div id="modalCard" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalCardLabel" style="padding: 15px;" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 id="modalCardLabel">
            <?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CARD_TITLE');?>
        </h3>
    </div>
    <div class="modal-body" style="padding: 35px;">
        <p style="font-weight: bold; font-size: 1.3em;" id="modalExpCardTitle"></p>
        <p id="cardValues"></p>
        <hr style="width: 80%;">
        <p style="font-weight: bold; font-size: 1.3em;" id="modalExpContacts"><?php echo JText::sprintf('COM_PROJECTS_HEAD_EXP_CONTACT_NAME');?></p>
        <p id="contactsValues"></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>