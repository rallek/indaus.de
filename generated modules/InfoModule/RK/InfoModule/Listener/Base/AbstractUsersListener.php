<?php
/**
 * Info.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\InfoModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\UsersModule\UserEvents;

/**
 * Event handler base class for events of the Users module.
 */
abstract class AbstractUsersListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::CONFIG_UPDATED => ['configUpdated', 5]
        ];
    }
    
    /**
     * Listener for the `module.users.config.updated` event.
     *
     * Occurs after the Users module configuration has been
     * updated via the administration interface.
     *
     * Event data is populated by the new values.
     *
     * @param GenericEvent $event The event instance
     */
    public function configUpdated(GenericEvent $event)
    {
    }
}
