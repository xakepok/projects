<div class="clearfix">
    <div class="js-stools-container-bar">
        <label for="filter_search" class="element-invisible"><?php echo JText::sprintf('JSEARCH_FILTER_LABEL'); ?></label>
        <div class="btn-wrapper input-append">
            <input type="text" autocomplete="off" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title aria-label="<?php echo JText::sprintf('COM_PROJECTS_FILTER_SECTION'); ?>"
                   data-original-title="<?php echo JText::sprintf('COM_PROJECTS_FILTER_SECTION'); ?>"
                   placeholder="<?php echo JText::sprintf('COM_PROJECTS_FILTER_SECTION'); ?>"
            >
            <button type="submit" class="btn hasTooltip">
                <?php echo JText::sprintf('JSEARCH_FILTER_SUBMIT'); ?>
                <span class="icon-search" aria-hidden="true"></span>
            </button>
        </div>
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::price($this->state->get('filter.price')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="clrFilters();this.form.submit();">
                <?php echo JText::sprintf('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style="">
        <?php
        if (is_numeric($this->state->get('filter.price')) && empty($this->items)) :
        ?>
            <div class="ordering-select hidden-phone">
                <div class="js-stools-field-list">
                    <?php echo ProjectsHtmlFilters::priceImport($this->state->get('filter.price')); ?>
                    <button type="button" class="btn hasTooltip" onclick="imp(<?php echo $this->state->get('filter.price');?>)">
                        <?php echo JText::sprintf('COM_PROJECTS_ACTION_IMPORT'); ?>
                        <span class="" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
