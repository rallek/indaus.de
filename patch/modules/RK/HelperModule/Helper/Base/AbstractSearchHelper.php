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

namespace RK\HelperModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Core\RouteUrl;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;
use RK\HelperModule\Entity\Factory\HelperFactory;
use RK\HelperModule\Helper\ControllerHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper implements SearchableInterface
{
    /**
     * @var PermissionApi
     */
    protected $permissionApi;
    
    /**
     * @var EngineInterface
     */
    private $templateEngine;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var HelperFactory
     */
    private $entityFactory;
    
    /**
     * @var ControllerHelper
     */
    private $controllerHelper;
    
    /**
     * SearchHelper constructor.
     *
     * @param PermissionApi    $permissionApi   PermissionApi service instance
     * @param EngineInterface  $templateEngine  Template engine service instance
     * @param SessionInterface $session         Session service instance
     * @param RequestStack     $requestStack    RequestStack service instance
     * @param HelperFactory $entityFactory EntityFactory service instance
     * @param ControllerHelper $controllerHelper ControllerHelper service instance
     */
    public function __construct(
        PermissionApi $permissionApi,
        EngineInterface $templateEngine,
        SessionInterface $session,
        RequestStack $requestStack,
        HelperFactory $entityFactory,
        ControllerHelper $controllerHelper) {
        $this->permissionApi = $permissionApi;
        $this->templateEngine = $templateEngine;
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions($active, $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('RKHelperModule::', '::', ACCESS_READ)) {
            return '';
        }
    
        $templateParameters = [];
    
        $searchTypes = ['linker', 'carouselItem', 'carousel', 'image', 'info'];
        foreach ($searchTypes as $searchType) {
            $templateParameters['active_' . $searchType] = !isset($args['rKHelperModuleSearchTypes']) || in_array($searchType, $args['rKHelperModuleSearchTypes']);
        }
    
        return $this->templateEngine->renderResponse('@RKHelperModule/Search/options.html.twig', $templateParameters)->getContent();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('RKHelperModule::', '::', ACCESS_READ)) {
            return [];
        }
    
        // initialise array for results
        $results = [];
    
        // retrieve list of activated object types
        $searchTypes = isset($modVars['objectTypes']) ? (array)$modVars['objectTypes'] : [];
        if (!is_array($searchTypes) || !count($searchTypes)) {
            if ($this->request->isMethod('GET')) {
                $searchTypes = $this->request->query->get('rKHelperModuleSearchTypes', []);
            } elseif ($this->request->isMethod('POST')) {
                $searchTypes = $this->request->request->get('rKHelperModuleSearchTypes', []);
            }
        }
    
        $allowedTypes = $this->controllerHelper->getObjectTypes('helper', ['helper' => 'search', 'action' => 'getResults']);
    
        foreach ($searchTypes as $objectType) {
            if (!in_array($objectType, $allowedTypes)) {
                continue;
            }
    
            $whereArray = [];
            $languageField = null;
            switch ($objectType) {
                case 'linker':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.linkerImage';
                    $whereArray[] = 'tbl.linkerHeadline';
                    $whereArray[] = 'tbl.linkerText';
                    $whereArray[] = 'tbl.theLink';
                    $whereArray[] = 'tbl.boostrapSetting';
                    $whereArray[] = 'tbl.linkerLocale';
                    $whereArray[] = 'tbl.linkerGroup';
                    break;
                case 'carouselItem':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.itemName';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.subtitle';
                    $whereArray[] = 'tbl.link';
                    $whereArray[] = 'tbl.itemImage';
                    $whereArray[] = 'tbl.titleColor';
                    $whereArray[] = 'tbl.singleItemIdentifier';
                    $whereArray[] = 'tbl.itemLocale';
                    break;
                case 'carousel':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.carouselName';
                    $whereArray[] = 'tbl.remarks';
                    $whereArray[] = 'tbl.carouselGroup';
                    $whereArray[] = 'tbl.carouselLocale';
                    break;
                case 'image':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.imageTitle';
                    $whereArray[] = 'tbl.myImage';
                    $whereArray[] = 'tbl.myDescription';
                    $whereArray[] = 'tbl.copyright';
                    break;
                case 'info':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.infoTitle';
                    $whereArray[] = 'tbl.infoDescription';
                    $whereArray[] = 'tbl.infoLocale';
                    break;
            }
    
            $repository = $this->entityFactory->getRepository($objectType);
    
            // build the search query without any joins
            $qb = $repository->genericBaseQuery('', '', false);
    
            // build where expression for given search type
            $whereExpr = $this->formatWhere($qb, $words, $whereArray, $searchType);
            $qb->andWhere($whereExpr);
    
            $query = $qb->getQuery();
    
            // set a sensitive limit
            $query->setFirstResult(0)
                  ->setMaxResults(250);
    
            // fetch the results
            $entities = $query->getResult();
    
            if (count($entities) == 0) {
                continue;
            }
    
            $descriptionField = $repository->getDescriptionFieldName();
    
            $entitiesWithDisplayAction = ['linker', 'carouselItem', 'image', 'info'];
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                $instanceId = $entity->createCompositeIdentifier();
                // perform permission check
                if (!$this->permissionApi->hasPermission('RKHelperModule:' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                $description = !empty($descriptionField) ? $entity[$descriptionField] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $this->request->getLocale();
    
                $displayUrl = $hasDisplayAction ? new RouteUrl('rkhelpermodule_' . $objectType . '_display', $urlArgs) : '';
    
                $result = new SearchResultEntity();
                $result->setTitle($entity->getTitleFromDisplayPattern())
                    ->setText($description)
                    ->setModule('RKHelperModule')
                    ->setCreated($created)
                    ->setSesid($this->session->getId())
                    ->setUrl($displayUrl);
                $results[] = $result;
            }
        }
    
        return $results;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return [];
    }
    
    /**
     * Construct a QueryBuilder Where orX|andX Expr instance.
     *
     * @param QueryBuilder $qb
     * @param array $words the words to query for
     * @param array $fields
     * @param string $searchtype AND|OR|EXACT
     *
     * @return null|Composite
     */
    protected function formatWhere(QueryBuilder $qb, array $words, array $fields, $searchtype = 'AND')
    {
        if (empty($words) || empty($fields)) {
            return null;
        }
    
        $method = ($searchtype == 'OR') ? 'orX' : 'andX';
        /** @var $where Composite */
        $where = $qb->expr()->$method();
        $i = 1;
        foreach ($words as $word) {
            $subWhere = $qb->expr()->orX();
            foreach ($fields as $field) {
                $expr = $qb->expr()->like($field, "?$i");
                $subWhere->add($expr);
                $qb->setParameter($i, '%' . $word . '%');
                $i++;
            }
            $where->add($subWhere);
        }
    
        return $where;
    }
}
