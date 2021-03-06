<?php
/**
 * Reference.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\ReferenceModule\Container\Base;

use Zikula\Bundle\HookBundle\AbstractHookContainer as ZikulaHookContainer;

use Zikula\Bundle\HookBundle\Bundle\SubscriberBundle;

/**
 * Base class for hook container methods.
 */
abstract class AbstractHookContainer extends ZikulaHookContainer
{
    /**
     * Define the hook bundles supported by this module.
     *
     * @return void
     */
    protected function setupHookBundles()
    {
        $bundle = new SubscriberBundle('RKReferenceModule', 'subscriber.rkreferencemodule.ui_hooks.attachedimages', 'ui_hooks', $this->__('rkreferencemodule. Attached images Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'rkreferencemodule.ui_hooks.attachedimages.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'rkreferencemodule.ui_hooks.attachedimages.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'rkreferencemodule.ui_hooks.attachedimages.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'rkreferencemodule.ui_hooks.attachedimages.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'rkreferencemodule.ui_hooks.attachedimages.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'rkreferencemodule.ui_hooks.attachedimages.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'rkreferencemodule.ui_hooks.attachedimages.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('RKReferenceModule', 'subscriber.rkreferencemodule.filter_hooks.attachedimages', 'filter_hooks', $this->__('rkreferencemodule. Attached images Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'rkreferencemodule.filter_hooks.attachedimages.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('RKReferenceModule', 'subscriber.rkreferencemodule.ui_hooks.activities', 'ui_hooks', $this->__('rkreferencemodule. Activities Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'rkreferencemodule.ui_hooks.activities.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'rkreferencemodule.ui_hooks.activities.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'rkreferencemodule.ui_hooks.activities.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'rkreferencemodule.ui_hooks.activities.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'rkreferencemodule.ui_hooks.activities.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'rkreferencemodule.ui_hooks.activities.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'rkreferencemodule.ui_hooks.activities.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('RKReferenceModule', 'subscriber.rkreferencemodule.filter_hooks.activities', 'filter_hooks', $this->__('rkreferencemodule. Activities Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'rkreferencemodule.filter_hooks.activities.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        
        
    }
}
