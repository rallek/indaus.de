'use strict';

function rKInfoCapitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation panel in list views.
 */
function rKInfoInitQuickNavigation()
{
    var quickNavForm;
    var objectType;

    if (jQuery('.rkinfomodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.rkinfomodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('rKInfoModule', '').replace('QuickNavForm', '');

    quickNavForm.find('select').change(function (event) {
        quickNavForm.submit();
    });

    var fieldPrefix = 'rkinfomodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('hidden')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('hidden');
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function rKInfoSimpleAlert(beforeElem, title, content, alertId, cssClass)
{
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given element
    beforeElem.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}

/**
 * Initialises the mass toggle functionality for admin view pages.
 */
function rKInfoInitMassToggle()
{
    if (jQuery('.rkinfo-mass-toggle').length > 0) {
        jQuery('.rkinfo-mass-toggle').click(function (event) {
            jQuery('.rkinfo-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
        });
    }
}

/**
 * Creates a dropdown menu for the item actions.
 */
function rKInfoInitItemActions(context)
{
    var containerSelector;
    var containers;
    var listClasses;

    containerSelector = '';
    if (context == 'view') {
        containerSelector = '.rkinfomodule-view';
        listClasses = 'list-unstyled dropdown-menu dropdown-menu-right';
    } else if (context == 'display') {
        containerSelector = 'h2, h3';
        listClasses = 'list-unstyled dropdown-menu';
    }

    if (containerSelector == '') {
        return;
    }

    containers = jQuery(containerSelector);
    if (containers.length < 1) {
        return;
    }

    containers.find('.dropdown > ul').removeClass('list-inline').addClass(listClasses);
    containers.find('.dropdown > ul a').each(function (index) {
        jQuery(this).html(jQuery(this).html() + jQuery(this).find('i').first().data('original-title'));
    });
    containers.find('.dropdown > ul a i').addClass('fa-fw');
    containers.find('.dropdown-toggle').removeClass('hidden').dropdown();
}

jQuery(document).ready(function() {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.rkinfomodule-view').length > 0;
    isDisplayPage = jQuery('.rkinfomodule-display').length > 0;

    if (isViewPage) {
        rKInfoInitQuickNavigation();
        rKInfoInitMassToggle();
        rKInfoInitItemActions('view');
    } else if (isDisplayPage) {
        rKInfoInitItemActions('display');
    }
});
