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

namespace RK\InfoModule\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use RuntimeException;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\Response\Ajax\AjaxResponse;
use Zikula\Core\Response\Ajax\BadDataResponse;
use Zikula\Core\Response\Ajax\FatalResponse;
use Zikula\Core\Response\Ajax\NotFoundResponse;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Retrieves a general purpose list of users.
     *
     * @param Request $request Current request instance
     *
     * @return JsonResponse
     */ 
    public function getCommonUsersListAction(Request $request)
    {
        if (!$this->hasPermission($this->name . '::Ajax', '::', ACCESS_EDIT)) {
            return true;
        }
        
        $fragment = '';
        if ($request->isMethod('POST') && $request->request->has('fragment')) {
            $fragment = $request->request->get('fragment', '');
        } elseif ($request->isMethod('GET') && $request->query->has('fragment')) {
            $fragment = $request->query->get('fragment', '');
        }
        
        $userRepository = $this->get('zikula_users_module.user_repository');
        $limit = 50;
        $filter = [
            'uname' => ['operator' => 'like', 'operand' => '%' . $fragment . '%']
        ];
        $results = $userRepository->query($filter, ['uname' => 'asc'], $limit);
        
        // load avatar plugin
        include_once 'lib/legacy/viewplugins/function.useravatar.php';
        $view = \Zikula_View::getInstance('RKInfoModule', false);
        
        $resultItems = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $resultItems[] = [
                    'uid' => $result->getUid(),
                    'uname' => $result->getUname(),
                    'avatar' => smarty_function_useravatar(['uid' => $result->getUid(), 'rating' => 'g'], $view)
                ];
            }
        }
        
        return new JsonResponse($resultItems);
    }
    
    /**
     * Retrieve item list for finder selections in Forms, Content type plugin and Scribite.
     *
     * @param string $ot      Name of currently used object type
     * @param string $sort    Sorting field
     * @param string $sortdir Sorting direction
     *
     * @return AjaxResponse
     */
    public function getItemListFinderAction(Request $request)
    {
        if (!$this->hasPermission($this->name . '::Ajax', '::', ACCESS_EDIT)) {
            return true;
        }
        
        $objectType = 'information';
        if ($request->isMethod('POST') && $request->request->has('ot')) {
            $objectType = $request->request->getAlnum('ot', 'information');
        } elseif ($request->isMethod('GET') && $request->query->has('ot')) {
            $objectType = $request->query->getAlnum('ot', 'information');
        }
        $controllerHelper = $this->get('rk_info_module.controller_helper');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListFinder'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $this->get('rk_info_module.entity_factory')->getRepository($objectType);
        $repository->setRequest($request);
        $selectionHelper = $this->get('rk_info_module.selection_helper');
        $idFields = $selectionHelper->getIdFields($objectType);
        
        $descriptionField = $repository->getDescriptionFieldName();
        
        $sort = $request->request->getAlnum('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = strtolower($request->request->getAlpha('sortdir', ''));
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        $where = ''; // filters are processed inside the repository class
        $sortParam = $sort . ' ' . $sdir;
        
        $entities = $repository->selectWhere($where, $sortParam);
        
        $slimItems = [];
        $component = $this->name . ':' . ucfirst($objectType) . ':';
        foreach ($entities as $item) {
            $itemId = '';
            foreach ($idFields as $idField) {
                $itemId .= ((!empty($itemId)) ? '_' : '') . $item[$idField];
            }
            if (!$this->hasPermission($component, $itemId . '::', ACCESS_READ)) {
                continue;
            }
            $slimItems[] = $this->prepareSlimItem($objectType, $item, $itemId, $descriptionField);
        }
        
        return new AjaxResponse($slimItems);
    }
    
    /**
     * Builds and returns a slim data array from a given entity.
     *
     * @param string $objectType       The currently treated object type
     * @param object $item             The currently treated entity
     * @param string $itemid           Data item identifier(s)
     * @param string $descriptionField Name of item description field
     *
     * @return array The slim data representation
     */
    protected function prepareSlimItem($objectType, $item, $itemId, $descriptionField)
    {
        $view = Zikula_View::getInstance('RKInfoModule', false);
        $view->assign($objectType, $item);
        $previewInfo = base64_encode($view->fetch('External/' . ucfirst($objectType) . '/info.html.twig'));
    
        $title = $item->getTitleFromDisplayPattern();
        $description = $descriptionField != '' ? $item[$descriptionField] : '';
    
        return [
            'id'          => $itemId,
            'title'       => str_replace('&amp;', '&', $title),
            'description' => $description,
            'previewInfo' => $previewInfo
        ];
    }
}
