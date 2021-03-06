<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\Base;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Zikula\Core\AbstractExtensionInstaller;
use Zikula_Workflow_Util;

/**
 * Installer base class.
 */
abstract class AbstractHelperModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * Install the RKHelperModule application.
     *
     * @return boolean True on success, or false
     *
     * @throws RuntimeException Thrown if database tables can not be created or another error occurs
     */
    public function install()
    {
        $logger = $this->container->get('logger');
        $userName = $this->container->get('zikula_users_module.current_user')->get('uname');
    
        // Check if upload directories exist and if needed create them
        try {
            $container = $this->container;
            $uploadHelper = new \RK\HelperModule\Helper\UploadHelper($container->get('translator.default'), $container->get('session'), $container->get('logger'), $container->get('zikula_users_module.current_user'), $container->get('zikula_extensions_module.api.variable'), $container->getParameter('datadir'));
            $uploadHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            $logger->error('{app}: User {user} could not create upload folders during installation. Error details: {errorMessage}.', ['app' => 'RKHelperModule', 'user' => $userName, 'errorMessage' => $e->getMessage()]);
        
            return false;
        }
        // create all tables from according entity definitions
        try {
            $this->schemaTool->create($this->listEntityClasses());
        } catch (\Exception $e) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $e->getMessage());
            $logger->error('{app}: Could not create the database tables during installation. Error details: {errorMessage}.', ['app' => 'RKHelperModule', 'errorMessage' => $e->getMessage()]);
    
            return false;
        }
    
        // set up all our vars with initial values
        $this->setVar('linkerEntriesPerPage', 10);
        $this->setVar('linkOwnLinkersOnAccountPage', true);
        $this->setVar('carouselItemEntriesPerPage', 10);
        $this->setVar('linkOwnCarouselItemsOnAccountPage', true);
        $this->setVar('carouselEntriesPerPage', 10);
        $this->setVar('linkOwnCarousellsOnAccountPage', true);
        $this->setVar('imageEntriesPerPage', 10);
        $this->setVar('linkOwnImagesOnAccountPage', true);
        $this->setVar('infoEntriesPerPage', 10);
        $this->setVar('linkOwnInfosOnAccountPage', true);
        $this->setVar('enableShrinkingForLinkerLinkerImage', false);
        $this->setVar('shrinkWidthLinkerLinkerImage', 800);
        $this->setVar('shrinkHeightLinkerLinkerImage', 600);
        $this->setVar('thumbnailModeLinkerLinkerImage',  'inset' );
        $this->setVar('thumbnailWidthLinkerLinkerImageView', 32);
        $this->setVar('thumbnailHeightLinkerLinkerImageView', 24);
        $this->setVar('thumbnailWidthLinkerLinkerImageDisplay', 240);
        $this->setVar('thumbnailHeightLinkerLinkerImageDisplay', 180);
        $this->setVar('thumbnailWidthLinkerLinkerImageEdit', 240);
        $this->setVar('thumbnailHeightLinkerLinkerImageEdit', 180);
        $this->setVar('enableShrinkingForCarouselItemItemImage', false);
        $this->setVar('shrinkWidthCarouselItemItemImage', 800);
        $this->setVar('shrinkHeightCarouselItemItemImage', 600);
        $this->setVar('thumbnailModeCarouselItemItemImage',  'inset' );
        $this->setVar('thumbnailWidthCarouselItemItemImageView', 32);
        $this->setVar('thumbnailHeightCarouselItemItemImageView', 24);
        $this->setVar('thumbnailWidthCarouselItemItemImageDisplay', 240);
        $this->setVar('thumbnailHeightCarouselItemItemImageDisplay', 180);
        $this->setVar('thumbnailWidthCarouselItemItemImageEdit', 240);
        $this->setVar('thumbnailHeightCarouselItemItemImageEdit', 180);
        $this->setVar('enableShrinkingForImageMyImage', false);
        $this->setVar('shrinkWidthImageMyImage', 800);
        $this->setVar('shrinkHeightImageMyImage', 600);
        $this->setVar('thumbnailModeImageMyImage',  'inset' );
        $this->setVar('thumbnailWidthImageMyImageView', 32);
        $this->setVar('thumbnailHeightImageMyImageView', 24);
        $this->setVar('thumbnailWidthImageMyImageDisplay', 240);
        $this->setVar('thumbnailHeightImageMyImageDisplay', 180);
        $this->setVar('thumbnailWidthImageMyImageEdit', 240);
        $this->setVar('thumbnailHeightImageMyImageEdit', 180);
    
        // create the default data
        $this->createDefaultData();
    
        // install subscriber hooks
        $this->hookApi->installSubscriberHooks($this->bundle->getMetaData());
        
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the RKHelperModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
    /*
        $logger = $this->container->get('logger');
    
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $e) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $e->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'RKHelperModule', 'errorMessage' => $e->getMessage()]);
    
                    return false;
                }
        }
    
        // Note there are several helpers available for making migrating your extension from Zikula 1.3 to 1.4 easier.
        // The following convenience methods are each responsible for a single aspect of upgrading to Zikula 1.4.x.
    
        // here is a possible usage example
        // of course 1.2.3 should match the number you used for the last stable 1.3.x module version.
        /* if ($oldVersion = '1.2.3') {
            // rename module for all modvars
            $this->updateModVarsTo14();
            
            // update extension information about this app
            $this->updateExtensionInfoFor14();
            
            // rename existing permission rules
            $this->renamePermissionsFor14();
            
            // rename all tables
            $this->renameTablesFor14();
            
            // remove event handler definitions from database
            $this->dropEventHandlersFromDatabase();
            
            // update module name in the hook tables
            $this->updateHookNamesFor14();
            
            // update module name in the workflows table
            $this->updateWorkflowsFor14();
        } * /
    */
    
        // update successful
        return true;
    }
    
    /**
     * Renames the module name for variables in the module_vars table.
     */
    protected function updateModVarsTo14()
    {
        $dbName = $this->getDbName();
        $conn = $this->getConnection();
    
        $conn->executeQuery("
            UPDATE $dbName.module_vars
            SET modname = 'RKHelperModule'
            WHERE modname = 'Helper';
        ");
    }
    
    /**
     * Renames this application in the core's extensions table.
     */
    protected function updateExtensionInfoFor14()
    {
        $conn = $this->getConnection();
        $dbName = $this->getDbName();
    
        $conn->executeQuery("
            UPDATE $dbName.modules
            SET name = 'RKHelperModule',
                directory = 'RK/HelperModule'
            WHERE name = 'Helper';
        ");
    }
    
    /**
     * Renames all permission rules stored for this app.
     */
    protected function renamePermissionsFor14()
    {
        $conn = $this->getConnection();
        $dbName = $this->getDbName();
    
        $componentLength = strlen('Helper') + 1;
    
        $conn->executeQuery("
            UPDATE $dbName.group_perms
            SET component = CONCAT('RKHelperModule', SUBSTRING(component, $componentLength))
            WHERE component LIKE 'Helper%';
        ");
    }
    
    /**
     * Renames all (existing) tables of this app.
     */
    protected function renameTablesFor14()
    {
        $conn = $this->getConnection();
        $dbName = $this->getDbName();
    
        $oldPrefix = 'helper_';
        $oldPrefixLength = strlen($oldPrefix);
        $newPrefix = 'rk_helper_';
    
        $sm = $conn->getSchemaManager();
        $tables = $sm->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if (substr($tableName, 0, $oldPrefixLength) != $oldPrefix) {
                continue;
            }
    
            $newTableName = str_replace($oldPrefix, $newPrefix, $tableName);
    
            $conn->executeQuery("
                RENAME TABLE $dbName.$tableName
                TO $dbName.$newTableName;
            ");
        }
    }
    
    /**
     * Removes event handlers from database as they are now described by service definitions and managed by dependency injection.
     */
    protected function dropEventHandlersFromDatabase()
    {
        \EventUtil::unregisterPersistentModuleHandlers('Helper');
    }
    
    /**
     * Updates the module name in the hook tables.
     */
    protected function updateHookNamesFor14()
    {
        $conn = $this->getConnection();
        $dbName = $this->getDbName();
    
        $conn->executeQuery("
            UPDATE $dbName.hook_area
            SET owner = 'RKHelperModule'
            WHERE owner = 'Helper';
        ");
    
        $componentLength = strlen('subscriber.helper') + 1;
        $conn->executeQuery("
            UPDATE $dbName.hook_area
            SET areaname = CONCAT('subscriber.rkhelpermodule', SUBSTRING(areaname, $componentLength))
            WHERE areaname LIKE 'subscriber.helper%';
        ");
    
        $conn->executeQuery("
            UPDATE $dbName.hook_binding
            SET sowner = 'RKHelperModule'
            WHERE sowner = 'Helper';
        ");
    
        $conn->executeQuery("
            UPDATE $dbName.hook_runtime
            SET sowner = 'RKHelperModule'
            WHERE sowner = 'Helper';
        ");
    
        $componentLength = strlen('helper') + 1;
        $conn->executeQuery("
            UPDATE $dbName.hook_runtime
            SET eventname = CONCAT('rkhelpermodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'helper%';
        ");
    
        $conn->executeQuery("
            UPDATE $dbName.hook_subscriber
            SET owner = 'RKHelperModule'
            WHERE owner = 'Helper';
        ");
    
        $componentLength = strlen('helper') + 1;
        $conn->executeQuery("
            UPDATE $dbName.hook_subscriber
            SET eventname = CONCAT('rkhelpermodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'helper%';
        ");
    }
    
    /**
     * Updates the module name in the workflows table.
     */
    protected function updateWorkflowsFor14()
    {
        $conn = $this->getConnection();
        $dbName = $this->getDbName();
    
        $conn->executeQuery("
            UPDATE $dbName.workflows
            SET module = 'RKHelperModule'
            WHERE module = 'Helper';
        ");
    }
    
    /**
     * Returns connection to the database.
     *
     * @return Connection the current connection
     */
    protected function getConnection()
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $connection = $entityManager->getConnection();
    
        return $connection;
    }
    
    /**
     * Returns the name of the default system database.
     *
     * @return string the database name
     */
    protected function getDbName()
    {
        return $this->container->getParameter('database_name');
    }
    
    /**
     * Uninstall RKHelperModule.
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables or stored workflows can not be removed
     */
    public function uninstall()
    {
        $logger = $this->container->get('logger');
    
        // delete stored object workflows
        $result = Zikula_Workflow_Util::deleteWorkflowsForModule('RKHelperModule');
        if (false === $result) {
            $this->addFlash('error', $this->__f('An error was encountered while removing stored object workflows for the %s extension.', ['%s' => 'RKHelperModule']));
            $logger->error('{app}: Could not remove stored object workflows during uninstallation.', ['app' => 'RKHelperModule']);
    
            return false;
        }
    
        try {
            $this->schemaTool->drop($this->listEntityClasses());
        } catch (\Exception $e) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $e->getMessage());
            $logger->error('{app}: Could not remove the database tables during uninstallation. Error details: {errorMessage}.', ['app' => 'RKHelperModule', 'errorMessage' => $e->getMessage()]);
    
            return false;
        }
    
        // uninstall subscriber hooks
        $this->hookApi->uninstallSubscriberHooks($this->bundle->getMetaData());
        
    
        // remove all module vars
        $this->delVars();
    
        // remove all thumbnails
        $manager = $this->container->get('systemplugin.imagine.manager');
        $manager->setModule('RKHelperModule');
        $manager->cleanupModuleThumbs();
    
        // remind user about upload folders not being deleted
        $uploadPath = $this->container->getParameter('datadir') . '/RKHelperModule/';
        $this->addFlash('status', $this->__f('The upload directories at [%s] can be removed manually.', ['%s' => $uploadPath]));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for RKHelperModule.
     *
     * @return array list of class names
     */
    protected function listEntityClasses()
    {
        $classNames = [];
        $classNames[] = 'RK\HelperModule\Entity\LinkerEntity';
        $classNames[] = 'RK\HelperModule\Entity\CarouselItemEntity';
        $classNames[] = 'RK\HelperModule\Entity\CarouselEntity';
        $classNames[] = 'RK\HelperModule\Entity\ImageEntity';
        $classNames[] = 'RK\HelperModule\Entity\InfoEntity';
        $classNames[] = 'RK\HelperModule\Entity\InfoTranslationEntity';
    
        return $classNames;
    }
    
    /**
     * Create the default data for RKHelperModule.
     *
     * @return void
     */
    protected function createDefaultData()
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $logger = $this->container->get('logger');
        $request = $this->container->get('request_stack')->getCurrentRequest();
        
        $entityClass = 'RK\HelperModule\Entity\LinkerEntity';
        $entityManager->getRepository($entityClass)->truncateTable($logger);
        $entityClass = 'RK\HelperModule\Entity\CarouselItemEntity';
        $entityManager->getRepository($entityClass)->truncateTable($logger);
        $entityClass = 'RK\HelperModule\Entity\CarouselEntity';
        $entityManager->getRepository($entityClass)->truncateTable($logger);
        $entityClass = 'RK\HelperModule\Entity\ImageEntity';
        $entityManager->getRepository($entityClass)->truncateTable($logger);
        $entityClass = 'RK\HelperModule\Entity\InfoEntity';
        $entityManager->getRepository($entityClass)->truncateTable($logger);
    }
}
