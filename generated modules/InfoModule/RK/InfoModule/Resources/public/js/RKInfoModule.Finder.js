'use strict';

var currentRKInfoModuleEditor = null;
var currentRKInfoModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getRKInfoModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function RKInfoModuleFinderCKEditor(editor, infoUrl)
{
    // Save editor for access in selector window
    currentRKInfoModuleEditor = editor;

    editor.popup(
        Routing.generate('rkinfomodule_external_finder', { objectType: 'information', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var rKInfoModule = {};

rKInfoModule.finder = {};

rKInfoModule.finder.onLoad = function (baseId, selectedId)
{
    jQuery('select').not("[id$='pasteAs']").change(rKInfoModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(rKInfoModule.finder.handleCancel);

    var selectedItems = jQuery('#rkinfomoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        rKInfoModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

rKInfoModule.finder.onParamChanged = function ()
{
    jQuery('#rKInfoModuleSelectorForm').submit();
};

rKInfoModule.finder.handleCancel = function ()
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        rKInfoClosePopup();
    } else if ('ckeditor' === editor) {
        rKInfoClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function rKInfoGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '2') {
        return '' + itemId;
    }

    // link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
rKInfoModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = rKInfoGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentRKInfoModuleEditor) {
            html = rKInfoGetPasteSnippet('html', itemId);

            window.opener.currentRKInfoModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    rKInfoClosePopup();
};

function rKInfoClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// RKInfoModule item selector for Forms
//=============================================================================

rKInfoModule.itemSelector = {};
rKInfoModule.itemSelector.items = {};
rKInfoModule.itemSelector.baseId = 0;
rKInfoModule.itemSelector.selectedId = 0;

rKInfoModule.itemSelector.onLoad = function (baseId, selectedId)
{
    rKInfoModule.itemSelector.baseId = baseId;
    rKInfoModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#rKInfoModuleObjectType').change(rKInfoModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(rKInfoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(rKInfoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(rKInfoModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(rKInfoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(rKInfoModule.itemSelector.onParamChanged);
    jQuery('#rKInfoModuleSearchGo').click(rKInfoModule.itemSelector.onParamChanged);
    jQuery('#rKInfoModuleSearchGo').keypress(rKInfoModule.itemSelector.onParamChanged);

    rKInfoModule.itemSelector.getItemList();
};

rKInfoModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajax_indicator').removeClass('hidden');

    rKInfoModule.itemSelector.getItemList();
};

rKInfoModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = info.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.ajax({
        type: 'POST',
        url: Routing.generate('rkinfomodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = rKInfoModule.itemSelector.baseId;
        rKInfoModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
        rKInfoModule.itemSelector.updateItemDropdownEntries();
        rKInfoModule.itemSelector.updatePreview();
    });
};

rKInfoModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = rKInfoModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = rKInfoModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (rKInfoModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(rKInfoModule.itemSelector.selectedId);
    }
};

rKInfoModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = rKInfoModule.itemSelector.baseId;
    items = rKInfoModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (rKInfoModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === rKInfoModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

rKInfoModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = rKInfoModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    preview = window.atob(rKInfoModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    rKInfoModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};
