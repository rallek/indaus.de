<?php
/**
 * Info.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.1 (http://modulestudio.de).
 */

/**
 * Delete operation.
 *
 * @param object $entity The treated object
 * @param array  $params Additional arguments
 *
 * @return bool False on failure or true if everything worked well
 *
 * @throws RuntimeException Thrown if executing the workflow action fails
 */
function RKInfoModule_operation_delete(&$entity, $params)
{

    // get attributes read from the workflow
    if (isset($params['nextstate']) && !empty($params['nextstate'])) {
        // assign value to the data object
        $entity['workflowState'] = $params['nextstate'];
    }
    
    // get entity manager
    $serviceManager = \ServiceUtil::getManager();
    $entityManager = $serviceManager->get('doctrine.orm.default_entity_manager');
    $logger = $serviceManager->get('logger');
    $logArgs = ['app' => 'RKInfoModule', 'user' => $serviceManager->get('zikula_users_module.current_user')->get('uname')];
    
    // delete entity
    try {
        $entityManager->remove($entity);
        $entityManager->flush();
        $result = true;
        $logger->notice('{app}: User {user} deleted an entity.', $logArgs);
    } catch (\Exception $e) {
        $logger->error('{app}: User {user} tried to delete an entity, but failed.', $logArgs);
        throw new \RuntimeException($e->getMessage());
    }

    // return result of this operation
    return $result;
}
