<div class="clearfix">
    <div class="js-stools-container-bar">
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::project($this->state->get('filter.project')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="clrFilters();this.form.submit();">
                <?php echo JText::sprintf('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style=""></div>
</div>
