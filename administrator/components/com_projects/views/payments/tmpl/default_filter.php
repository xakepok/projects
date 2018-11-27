<div class="clearfix">
    <div class="js-stools-container-bar">
        <div class="js-stools-field-filter">
            <?php echo ProjectsHtmlFilters::contract($this->state->get('filter.contract')); ?>
            <?php echo ProjectsHtmlFilters::exhibitor($this->state->get('filter.exhibitor')); ?>
            <?php echo ProjectsHtmlFilters::project($this->state->get('filter.project')); ?>
            <?php echo ProjectsHtmlFilters::stateScore($this->state->get('filter.state')); ?>
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
