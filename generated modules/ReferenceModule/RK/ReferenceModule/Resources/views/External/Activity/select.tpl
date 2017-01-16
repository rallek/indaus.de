{* Purpose of this template: Display a popup selector for Forms and Content integration *}
{assign var='baseID' value='activity'}
<div id="{$baseID}Preview" style="float: right; width: 300px; border: 1px dotted #a3a3a3; padding: .2em .5em; margin-right: 1em">
    <p><strong>{gt text='Activity information'}</strong></p>
    {img id='ajax_indicator' modname='core' set='ajax' src='indicator_circle.gif' alt='' class='hidden'}
    <div id="{$baseID}PreviewContainer">&nbsp;</div>
</div>
<br />
<br />
{assign var='leftSide' value=' style="float: left; width: 10em"'}
{assign var='rightSide' value=' style="float: left"'}
{assign var='break' value=' style="clear: left"'}
<p>
    <label for="{$baseID}Id"{$leftSide}>{gt text='Activity'}:</label>
    <select id="{$baseID}Id" name="id"{$rightSide}>
        {foreach item='activity' from=$items}
            <option value="{$activity.id}"{if $selectedId eq $activity.id} selected="selected"{/if}>{$activity->getTitleFromDisplayPattern()}</option>
        {foreachelse}
            <option value="0">{gt text='No entries found.'}</option>
        {/foreach}
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}Sort"{$leftSide}>{gt text='Sort by'}:</label>
    <select id="{$baseID}Sort" name="sort"{$rightSide}>
        <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
        <option value="workflowState"{if $sort eq 'workflowState'} selected="selected"{/if}>{gt text='Workflow state'}</option>
        <option value="displayLanguage"{if $sort eq 'displayLanguage'} selected="selected"{/if}>{gt text='Display language'}</option>
        <option value="title"{if $sort eq 'title'} selected="selected"{/if}>{gt text='Title'}</option>
        <option value="titleImage"{if $sort eq 'titleImage'} selected="selected"{/if}>{gt text='Title image'}</option>
        <option value="copyrightTitleImage"{if $sort eq 'copyrightTitleImage'} selected="selected"{/if}>{gt text='Copyright title image'}</option>
        <option value="referenceImage"{if $sort eq 'referenceImage'} selected="selected"{/if}>{gt text='Reference image'}</option>
        <option value="copyrightReferenceImage"{if $sort eq 'copyrightReferenceImage'} selected="selected"{/if}>{gt text='Copyright reference image'}</option>
        <option value="teaserDescription"{if $sort eq 'teaserDescription'} selected="selected"{/if}>{gt text='Teaser description'}</option>
        <option value="activityDescription"{if $sort eq 'activityDescription'} selected="selected"{/if}>{gt text='Activity description'}</option>
        <option value="infoField1"{if $sort eq 'infoField1'} selected="selected"{/if}>{gt text='Info field 1'}</option>
        <option value="infoField2"{if $sort eq 'infoField2'} selected="selected"{/if}>{gt text='Info field 2'}</option>
        <option value="infoField3"{if $sort eq 'infoField3'} selected="selected"{/if}>{gt text='Info field 3'}</option>
        <option value="infoField4"{if $sort eq 'infoField4'} selected="selected"{/if}>{gt text='Info field 4'}</option>
        <option value="infoField5"{if $sort eq 'infoField5'} selected="selected"{/if}>{gt text='Info field 5'}</option>
        <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
        <option value="createdBy"{if $sort eq 'createdBy'} selected="selected"{/if}>{gt text='Creator'}</option>
        <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
    </select>
    <select id="{$baseID}SortDir" name="sortdir" class="form-control">
        <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
        <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
    </select>
    <br{$break} />
</p>
<p>
    <label for="{$baseID}SearchTerm"{$leftSide}>{gt text='Search for'}:</label>
    <input type="text" id="{$baseID}SearchTerm" name="q" class="form-control"{$rightSide} />
    <input type="button" id="rKReferenceModuleSearchGo" name="gosearch" value="{gt text='Filter'}" class="btn btn-default" />
    <br{$break} />
</p>
<br />
<br />

<script type="text/javascript">
/* <![CDATA[ */
    ( function($) {
        $(document).ready(function() {
            rKReferenceModule.itemSelector.onLoad('{{$baseID}}', {{$selectedId|default:0}});
        });
    })(jQuery);
/* ]]> */
</script>
