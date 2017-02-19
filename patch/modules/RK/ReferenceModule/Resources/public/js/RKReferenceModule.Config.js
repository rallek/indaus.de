'use strict';

function refereToggleShrinkSettings(fieldName) {
    var idSuffix = fieldName.replace('rkreferencemodule_appsettings_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#rkreferencemodule_appsettings_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function() {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            refereToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        refereToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
