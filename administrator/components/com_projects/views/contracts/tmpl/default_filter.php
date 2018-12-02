<div class="clearfix">
    <div class="js-stools-container-bar">
        <label for="filter_search" class="element-invisible"><?php echo JText::sprintf('JSEARCH_FILTER_LABEL'); ?></label>
        <div class="btn-wrapper input-append">
            <input type="text" autocomplete="off" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title aria-label="<?php echo JText::sprintf('COM_PROJECTS_FILTER_CONTRACT'); ?>"
                   data-original-title="<?php echo JText::sprintf('COM_PROJECTS_FILTER_CONTRACT'); ?>"
                   placeholder="<?php echo JText::sprintf('COM_PROJECTS_FILTER_CONTRACT'); ?>"
            >
            <button type="submit" class="btn hasTooltip">
                <?php echo JText::sprintf('JSEARCH_FILTER_SUBMIT'); ?>
                <span class="icon-search" aria-hidden="true"></span>
            </button>
        </div>
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::project($this->state->get('filter.project')); ?>
            <?php echo ProjectsHtmlFilters::exhibitor($this->state->get('filter.exhibitor')); ?>
            <?php echo ProjectsHtmlFilters::manager($this->state->get('filter.manager')); ?>
            <?php echo ProjectsHtmlFilters::status($this->state->get('filter.status')); ?>
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
