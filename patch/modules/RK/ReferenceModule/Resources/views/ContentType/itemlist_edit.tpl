{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='rkreferencemodule' assign='objectTypeSelectorLabel'}
    {formlabel for='rKReferenceModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {rkreferencemoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='rKReferenceModuleObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='rkreferencemodule'}</span>
    </div>
</div>

<div class="form-group">
    {gt text='Sorting' domain='rkreferencemodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='rKReferenceModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='rkreferencemodule' assign='sortingRandomLabel'}
        {formlabel for='rKReferenceModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='rKReferenceModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='rkreferencemodule' assign='sortingNewestLabel'}
        {formlabel for='rKReferenceModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='rKReferenceModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='rkreferencemodule' assign='sortingDefaultLabel'}
        {formlabel for='rKReferenceModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='rkreferencemodule' assign='amountLabel'}
    {formlabel for='rKReferenceModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='rKReferenceModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2 cssClass='form-control'}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='rkreferencemodule' assign='templateLabel'}
    {formlabel for='rKReferenceModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {rkreferencemoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='rKReferenceModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group"{* data-switch="rKReferenceModuleTemplate" data-switch-value="custom"*}>
    {gt text='Custom template' domain='rkreferencemodule' assign='customTemplateLabel'}
    {formlabel for='rKReferenceModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='rKReferenceModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='rkreferencemodule'}: <em>itemlist_[objectType]_display.html.twig</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='rkreferencemodule' assign='filterLabel'}
    {formlabel for='rKReferenceModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='rKReferenceModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        {*<span class="help-block">
            <a class="fa fa-filter" data-toggle="modal" data-target="#filterSyntaxModal">{gt text='Show syntax examples' domain='rkreferencemodule'}</a>
        </span>*}
    </div>
</div>

{*include file='include_filterSyntaxDialog.tpl'*}

<script type="text/javascript">
    (function($) {
    	$('#rKReferenceModuleTemplate').change(function() {
    	    $('#customTemplateArea').toggleClass('hidden', $(this).val() != 'custom');
	    }).trigger('change');
    })(jQuery)
</script>
