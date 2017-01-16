{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='rkinfomodule' assign='objectTypeSelectorLabel'}
    {formlabel for='rKInfoModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {rkinfomoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='rKInfoModuleOjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='rkinfomodule'}</span>
    </div>
</div>

{if $featureActivationHelper->isEnabled(const('RK\\InfoModule\\Helper\\FeatureActivationHelper::CATEGORIES', $objectType))}
{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="form-group">
            {assign var='hasMultiSelection' value=$categoryHelper->hasMultipleSelection($objectType, $propertyName)}
            {gt text='Category' domain='rkinfomodule' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='rkinfomodule' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="rKInfoModuleCatIds`$propertyName`" text=$categorySelectorLabel cssClass='col-sm-3 control-label'}
            <div class="col-sm-9">
                {formdropdownlist id="rKInfoModuleCatIds`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode cssClass='form-control'}
                <span class="help-block">{gt text='This is an optional filter.' domain='rkinfomodule'}</span>
            </div>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}
{/if}

<div class="form-group">
    {gt text='Sorting' domain='rkinfomodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='rKInfoModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='rkinfomodule' assign='sortingRandomLabel'}
        {formlabel for='rKInfoModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='rKInfoModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='rkinfomodule' assign='sortingNewestLabel'}
        {formlabel for='rKInfoModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='rKInfoModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='rkinfomodule' assign='sortingDefaultLabel'}
        {formlabel for='rKInfoModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='rkinfomodule' assign='amountLabel'}
    {formlabel for='rKInfoModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='rKInfoModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='rkinfomodule' assign='templateLabel'}
    {formlabel for='rKInfoModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {rkinfomoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='rKInfoModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group" data-switch="rKInfoModuleTemplate" data-switch-value="custom">
    {gt text='Custom template' domain='rkinfomodule' assign='customTemplateLabel'}
    {formlabel for='rKInfoModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='rKInfoModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='rkinfomodule'}: <em>itemlist_[objectType]_display.tpl</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='rkinfomodule' assign='filterLabel'}
    {formlabel for='rKInfoModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='rKInfoModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        {*<span class="help-block">
            <a class="fa fa-filter" data-toggle="modal" data-target="#filterSyntaxModal">{gt text='Show syntax examples' domain='rkinfomodule'}</a>
        </span>*}
    </div>
</div>

{*include file='include_filterSyntaxDialog.tpl'*}

{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap.min.css'}
{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap-theme.min.css'}
{pageaddvar name='javascript' value='web/bootstrap/js/bootstrap.min.js'}
