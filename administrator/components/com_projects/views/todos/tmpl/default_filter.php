<div class="clearfix">
    <div class="js-stools-container-bar">
        <label for="filter_search" class="element-invisible"><?php echo JText::sprintf('JSEARCH_FILTER_LABEL'); ?></label>
        <div class="btn-wrapper input-append">

        </div>
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::exhibitor($this->state->get('filter.exhibitor')); ?>
            <?php echo ProjectsHtmlFilters::project($this->state->get('filter.project')); ?>
            <?php echo ProjectsHtmlFilters::stateTodo($this->state->get('filter.state')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="clrFilters();this.form.submit();">
                <?php echo JText::sprintf('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style="">
        <?php echo ProjectsHtmlFilters::dat($this->state->get('filter.dat')); ?>
    </div>
</div>
