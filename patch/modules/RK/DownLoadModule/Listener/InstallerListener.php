<?php
/**
 * DownLoad.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://oldtimer-ig-osnabrueck.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\DownLoadModule\Listener;

use RK\DownLoadModule\Listener\Base\AbstractInstallerListener;
use Zikula\Core\Event\GenericEvent;
use Zikula\Core\Event\ModuleStateEvent;

/**
 * Event handler implementation class for module installer events.
 */
class InstallerListener extends AbstractInstallerListener
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return parent::getSubscribedEvents();
    }
    
    /**
     * {@inheritdoc}
     */
    public function moduleInstalled(ModuleStateEvent $event)
    {
        parent::moduleInstalled($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function modulePostInstalled(ModuleStateEvent $event)
    {
        parent::modulePostInstalled($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function moduleUpgraded(ModuleStateEvent $event)
    {
        parent::moduleUpgraded($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function moduleEnabled(ModuleStateEvent $event)
    {
        parent::moduleEnabled($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function moduleDisabled(ModuleStateEvent $event)
    {
        parent::moduleDisabled($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function moduleRemoved(ModuleStateEvent $event)
    {
        parent::moduleRemoved($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
    
    /**
     * {@inheritdoc}
     */
    public function subscriberAreaUninstalled(GenericEvent $event)
    {
        parent::subscriberAreaUninstalled($event);
    
        // you can access general data available in the event
        
        // the event name
        // echo 'Event: ' . $event->getName();
        
        // type of current request: MASTER_REQUEST or SUB_REQUEST
        // if a listener should only be active for the master request,
        // be sure to check that at the beginning of your method
        // if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
        //     // don't do anything if it's not the master request
        //     return;
        // }
        
        // kernel instance handling the current request
        // $kernel = $event->getKernel();
        
        // the currently handled request
        // $request = $event->getRequest();
    }
}
