<div class="clearfix">
    <div class="js-stools-container-bar">
        <label for="filter_search" class="element-invisible"><?php echo JText::sprintf('JSEARCH_FILTER_LABEL'); ?></label>
        <div class="btn-wrapper input-append">
            <input type="text" autocomplete="off" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title aria-label="<?php echo JText::sprintf('COM_PROJECTS_FILTER_EXHIBITOR'); ?>"
                   data-original-title="<?php echo JText::sprintf('COM_PROJECTS_FILTER_EXHIBITOR'); ?>"
                   placeholder="<?php echo JText::sprintf('COM_PROJECTS_FILTER_EXHIBITOR'); ?>"
            >
            <button type="submit" class="btn hasTooltip">
                <?php echo JText::sprintf('JSEARCH_FILTER_SUBMIT'); ?>
                <span class="icon-search" aria-hidden="true"></span>
            </button>
        </div>
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::activity($this->state->get('filter.activity')); ?>
            <?php echo ProjectsHtmlFilters::state($this->state->get('filter.state')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="document.getElementById('filter_search').value='';this.form.submit();">
                <?php echo JText::sprintf('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style=""></div>
</div>
