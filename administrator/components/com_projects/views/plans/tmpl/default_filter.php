<div class="clearfix">
    <div class="js-stools-container-bar">
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::project($this->state->get('filter.project')); ?>
            <?php echo ProjectsHtmlFilters::state($this->state->get('filter.state')); ?>
        </div>
        <div class="btn-wrapper">
            <button type="button" class="btn hasTooltip js-stools-btn-clear"
                    onclick="document.getElementById('filter_search').value='';this.form.submit();">
                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
            </button>
        </div>
    </div>
    <div class="js-stools-container-list hidden-phone hidden-tablet shown" style=""></div>
</div>
