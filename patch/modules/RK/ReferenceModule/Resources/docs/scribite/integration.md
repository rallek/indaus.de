# SCRIBITE INTEGRATION

It is easy to include RKReferenceModule in your [Scribite editors](https://github.com/zikula-modules/Scribite/).
RKReferenceModule contains already the a popup for selecting activities and other items.
Please note that Scribite 5.0+ is required for this.

To activate the popup for the editor of your choice (currently supported: CKEditor, TinyMCE)
you only need to add the plugin to the list of extra plugins in the editor configuration.

If such a configuration is not available for an editor check if the plugins for
RKReferenceModule are in Scribite/plugins/EDITOR/vendor/plugins. If not then copy the directories from
    modules/RK/ReferenceModule/Resources/docs//scribite/plugins into modules/Scribite/plugins.
