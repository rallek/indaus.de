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

namespace RK\HelperModule\Form\Handler\Common\Base;

use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\Core\RouteUrl;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PageLockModule\Api\LockingApi;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use RK\HelperModule\Entity\Factory\HelperFactory;
use RK\HelperModule\Helper\FeatureActivationHelper;
use RK\HelperModule\Helper\ControllerHelper;
use RK\HelperModule\Helper\HookHelper;
use RK\HelperModule\Helper\ModelHelper;
use RK\HelperModule\Helper\SelectionHelper;
use RK\HelperModule\Helper\TranslatableHelper;
use RK\HelperModule\Helper\WorkflowHelper;

/**
 * This handler class handles the page events of editing forms.
 * It collects common functionality required by different object types.
 */
abstract class AbstractEditHandler
{
    use TranslatorTrait;

    /**
     * Name of treated object type.
     *
     * @var string
     */
    protected $objectType;

    /**
     * Name of treated object type starting with upper case.
     *
     * @var string
     */
    protected $objectTypeCapital;

    /**
     * Lower case version.
     *
     * @var string
     */
    protected $objectTypeLower;

    /**
     * Permission component based on object type.
     *
     * @var string
     */
    protected $permissionComponent;

    /**
     * Reference to treated entity instance.
     *
     * @var EntityAccess
     */
    protected $entityRef = null;

    /**
     * List of identifier names.
     *
     * @var array
     */
    protected $idFields = [];

    /**
     * List of identifiers of treated entity.
     *
     * @var array
     */
    protected $idValues = [];

    /**
     * Code defining the redirect goal after command handling.
     *
     * @var string
     */
    protected $returnTo = null;

    /**
     * Whether a create action is going to be repeated or not.
     *
     * @var boolean
     */
    protected $repeatCreateAction = false;

    /**
     * Url of current form with all parameters for multiple creations.
     *
     * @var string
     */
    protected $repeatReturnUrl = null;
    
    /**
     * List of identifiers for predefined relationships.
     *
     * @var mixed
     */
    protected $relationPresets = [];

    /**
     * Full prefix for related items.
     *
     * @var string
     */
    protected $idPrefix = '';

    /**
     * Whether an existing item is used as template for a new one.
     *
     * @var boolean
     */
    protected $hasTemplateId = false;

    /**
     * Whether the PageLock extension is used for this entity type or not.
     *
     * @var boolean
     */
    protected $hasPageLockSupport = false;

    /**
     * Whether the entity has translatable fields or not.
     *
     * @var boolean
     */
    protected $hasTranslatableFields = false;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    /**
     * The router.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PermissionApi
     */
    protected $permissionApi;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * @var CurrentUserApi
     */
    protected $currentUserApi;

    /**
     * @var HelperFactory
     */
    protected $entityFactory;

    /**
     * @var ControllerHelper
     */
    protected $controllerHelper;

    /**
     * @var HookHelper
     */
    protected $hookHelper;

    /**
     * @var ModelHelper
     */
    protected $modelHelper;

    /**
     * @var SelectionHelper
     */
    protected $selectionHelper;

    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;

    /**
     * @var TranslatableHelper
     */
    protected $translatableHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * Reference to optional locking api.
     *
     * @var LockingApi
     */
    protected $lockingApi = null;

    /**
     * The handled form type.
     *
     * @var AbstractType
     */
    protected $form;

    /**
     * Template parameters.
     *
     * @var array
     */
    protected $templateParameters = [];

    /**
     * EditHandler constructor.
     *
     * @param KernelInterface      $kernel           Kernel service instance
     * @param TranslatorInterface  $translator       Translator service instance
     * @param FormFactoryInterface $formFactory      FormFactory service instance
     * @param RequestStack         $requestStack     RequestStack service instance
     * @param RouterInterface      $router           Router service instance
     * @param LoggerInterface      $logger           Logger service instance
     * @param PermissionApi        $permissionApi    PermissionApi service instance
     * @param VariableApi          $variableApi      VariableApi service instance
     * @param CurrentUserApi       $currentUserApi   CurrentUserApi service instance
     * @param HelperFactory $entityFactory HelperFactory service instance
     * @param ControllerHelper     $controllerHelper ControllerHelper service instance
     * @param ModelHelper          $modelHelper      ModelHelper service instance
     * @param SelectionHelper      $selectionHelper  SelectionHelper service instance
     * @param WorkflowHelper       $workflowHelper   WorkflowHelper service instance
     * @param HookHelper           $hookHelper       HookHelper service instance
     * @param TranslatableHelper   $translatableHelper TranslatableHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(
        KernelInterface $kernel,
        TranslatorInterface $translator,
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        RouterInterface $router,
        LoggerInterface $logger,
        PermissionApi $permissionApi,
        VariableApi $variableApi,
        CurrentUserApi $currentUserApi,
        HelperFactory $entityFactory,
        ControllerHelper $controllerHelper,
        ModelHelper $modelHelper,
        SelectionHelper $selectionHelper,
        WorkflowHelper $workflowHelper,
        HookHelper $hookHelper,
        TranslatableHelper $translatableHelper,
        FeatureActivationHelper $featureActivationHelper)
    {
        $this->kernel = $kernel;
        $this->setTranslator($translator);
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
        $this->logger = $logger;
        $this->permissionApi = $permissionApi;
        $this->variableApi = $variableApi;
        $this->currentUserApi = $currentUserApi;
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
        $this->modelHelper = $modelHelper;
        $this->selectionHelper = $selectionHelper;
        $this->workflowHelper = $workflowHelper;
        $this->hookHelper = $hookHelper;
        $this->translatableHelper = $translatableHelper;
        $this->featureActivationHelper = $featureActivationHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * Initialise form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @param array $templateParameters List of preassigned template variables
     *
     * @return boolean False in case of initialisation errors, otherwise true
     *
     * @throws RuntimeException Thrown if the workflow actions can not be determined
     */
    public function processForm(array $templateParameters)
    {
        $this->templateParameters = $templateParameters;
    
        $this->idPrefix = $this->request->query->getAlnum('idp', '');
    
        // initialise redirect goal
        $this->returnTo = $this->request->query->get('returnTo', null);
        if (null === $this->returnTo) {
            // default to referer
            if ($this->request->getSession()->has('rkhelpermoduleReferer')) {
                $this->returnTo = $this->request->getSession()->get('rkhelpermoduleReferer');
            } elseif ($this->request->headers->has('rkhelpermoduleReferer')) {
                $this->returnTo = $this->request->headers->get('rkhelpermoduleReferer');
                $this->request->getSession()->set('rkhelpermoduleReferer', $this->returnTo);
            } elseif ($this->request->server->has('HTTP_REFERER')) {
                $this->returnTo = $this->request->server->get('HTTP_REFERER');
                $this->request->getSession()->set('rkhelpermoduleReferer', $this->returnTo);
            }
        }
        // store current uri for repeated creations
        $this->repeatReturnUrl = $this->request->getSchemeAndHttpHost() . $this->request->getBasePath() . $this->request->getPathInfo();
    
        $this->permissionComponent = 'RKHelperModule:' . $this->objectTypeCapital . ':';
    
        $this->idFields = $this->selectionHelper->getIdFields($this->objectType);
    
        // retrieve identifier of the object we wish to view
        $this->idValues = $this->controllerHelper->retrieveIdentifier($this->request, [], $this->objectType, $this->idFields);
        $hasIdentifier = $this->controllerHelper->isValidIdentifier($this->idValues);
    
        $entity = null;
        $this->templateParameters['mode'] = $hasIdentifier ? 'edit' : 'create';
    
        if ($this->templateParameters['mode'] == 'edit') {
            if (!$this->permissionApi->hasPermission($this->permissionComponent, $this->createCompositeIdentifier() . '::', ACCESS_EDIT)) {
                throw new AccessDeniedException();
            }
    
            $entity = $this->initEntityForEditing();
            if (null !== $entity) {
                if (true === $this->hasPageLockSupport && $this->kernel->isBundle('ZikulaPageLockModule') && null !== $this->lockingApi) {
                    // try to guarantee that only one person at a time can be editing this entity
                    $lockName = 'RKHelperModule' . $this->objectTypeCapital . $this->createCompositeIdentifier();
                    $this->lockingApi->addLock($lockName, $this->getRedirectUrl(null));
                    // reload entity as the addLock call above has triggered the preUpdate event
                    $this->entityFactory->getObjectManager()->refresh($entity);
                }
            }
        } else {
            if (!$this->permissionApi->hasPermission($this->permissionComponent, '::', ACCESS_EDIT)) {
                throw new AccessDeniedException();
            }
    
            $entity = $this->initEntityForCreation();
    
            // set default values from request parameters
            foreach ($this->request->query->all() as $key => $value) {
                if (strlen($key) < 5 || substr($key, 0, 4) != 'set_') {
                    continue;
                }
                $fieldName = str_replace('set_', '', $key);
                $setterName = 'set' . ucfirst($fieldName);
                if (!method_exists($entity, $setterName)) {
                    continue;
                }
                $entity[$fieldName] = $value;
            }
        }
    
        if (null === $entity) {
            $this->request->getSession()->getFlashBag()->add('error', $this->__('No such item found.'));
    
            return new RedirectResponse($this->getRedirectUrl(['commandName' => 'cancel']), 302);
        }
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    
        
        if (true === $this->hasTranslatableFields) {
            if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::TRANSLATIONS, $this->objectType)) {
                $this->initTranslationsForEditing();
            }
        }
        
        $this->initRelationPresets();
    
        $actions = $this->workflowHelper->getActionsForObject($entity);
        if (false === $actions || !is_array($actions)) {
            $this->request->getSession()->getFlashBag()->add('error', $this->__('Error! Could not determine workflow actions.'));
            $logArgs = ['app' => 'RKHelperModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => $this->objectType, 'id' => $entity->createCompositeIdentifier()];
            $this->logger->error('{app}: User {user} tried to edit the {entity} with id {id}, but failed to determine available workflow actions.', $logArgs);
            throw new \RuntimeException($this->__('Error! Could not determine workflow actions.'));
        }
    
        $this->templateParameters['actions'] = $actions;
    
        $this->form = $this->createForm();
        if (!is_object($this->form)) {
            return false;
        }
    
        // handle form request and check validity constraints of edited entity
        if ($this->form->handleRequest($this->request) && $this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $result = $this->handleCommand();
                if (false === $result) {
                    $this->templateParameters['form'] = $this->form->createView();
                }
    
                return $result;
            }
            if ($this->form->get('cancel')->isClicked()) {
                return new RedirectResponse($this->getRedirectUrl(['commandName' => 'cancel']), 302);
            }
        }
    
        $this->templateParameters['form'] = $this->form->createView();
    
        // everything okay, no initialisation errors occured
        return true;
    }
    
    /**
     * Creates the form type.
     */
    protected function createForm()
    {
        // to be customised in sub classes
        return null;
    }
    
    
    /**
     * Initialises relationship presets.
     */
    protected function initRelationPresets()
    {
        // to be customised in sub classes
    }
    
    /**
     * Returns the template parameters.
     *
     * @return array
     */
    public function getTemplateParameters()
    {
        return $this->templateParameters;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers
     */
    protected function createCompositeIdentifier()
    {
        $itemId = '';
        foreach ($this->idFields as $idField) {
            if (!empty($itemId)) {
                $itemId .= '_';
            }
            $itemId .= $this->idValues[$idField];
        }
    
        return $itemId;
    }
    
    /**
     * Initialise existing entity for editing.
     *
     * @return EntityAccess|null Desired entity instance or null
     */
    protected function initEntityForEditing()
    {
        $entity = $this->selectionHelper->getEntity($this->objectType, $this->idValues);
        if (null === $entity) {
            return null;
        }
    
        $entity->initWorkflow();
    
        return $entity;
    }
    
    /**
     * Initialise new entity for creation.
     *
     * @return EntityAccess|null Desired entity instance or null
     */
    protected function initEntityForCreation()
    {
        $this->hasTemplateId = false;
        $templateId = $this->request->query->get('astemplate', '');
        $entity = null;
    
        if (!empty($templateId)) {
            $templateIdValueParts = explode('_', $templateId);
            $this->hasTemplateId = count($templateIdValueParts) == count($this->idFields);
    
            if (true === $this->hasTemplateId) {
                $templateIdValues = [];
                $i = 0;
                foreach ($this->idFields as $idField) {
                    $templateIdValues[$idField] = $templateIdValueParts[$i];
                    $i++;
                }
                // reuse existing entity
                $entityT = $this->selectionHelper->getEntity($this->objectType, $templateIdValues);
                if (null === $entityT) {
                    return null;
                }
                $entity = clone $entityT;
            }
        }
    
        if (null === $entity) {
            $createMethod = 'create' . ucfirst($this->objectType);
            $entity = $this->entityFactory->$createMethod();
        }
    
        return $entity;
    }
    
    /**
     * Initialise translations.
     */
    protected function initTranslationsForEditing()
    {
        $entity = $this->entityRef;
    
        // retrieve translated fields
        $translations = $this->translatableHelper->prepareEntityForEditing($this->objectType, $entity);
    
        // assign translations
        foreach ($translations as $language => $translationData) {
            $this->templateParameters[$this->objectTypeLower . $language] = $translationData;
        }
    
        // assign list of installed languages for translatable extension
        $this->templateParameters['supportedLanguages'] = $this->translatableHelper->getSupportedLanguages($this->objectType);
    }

    /**
     * Get list of allowed redirect codes.
     *
     * @return array list of possible redirect codes
     */
    protected function getRedirectCodes()
    {
        $codes = [];
    
        // to be filled by subclasses
    
        return $codes;
    }

    /**
     * Command event handler.
     *
     * @param array $args List of arguments
     *
     * @return mixed Redirect or false on errors
     */
    public function handleCommand($args = [])
    {
        // build $args for BC (e.g. used by redirect handling)
        foreach ($this->templateParameters['actions'] as $action) {
            if ($this->form->get($action['id'])->isClicked()) {
                $args['commandName'] = $action['id'];
            }
        }
        if ($this->form->get('cancel')->isClicked()) {
            $args['commandName'] = 'cancel';
        }
    
        $action = $args['commandName'];
        $isRegularAction = !in_array($action, ['delete', 'cancel']);
    
        if ($isRegularAction || $action == 'delete') {
            $this->fetchInputData($args);
        }
    
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        if ($entity->supportsHookSubscribers() && $action != 'cancel') {
            // Let any hooks perform additional validation actions
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
            $validationHooksPassed = $this->hookHelper->callValidationHooks($entity, $hookType);
            if (!$validationHooksPassed) {
                return false;
            }
        }
    
        if ($isRegularAction && true === $this->hasTranslatableFields) {
            if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::TRANSLATIONS, $this->objectType)) {
                $this->processTranslationsForUpdate();
            }
        }
    
        if ($isRegularAction || $action == 'delete') {
            $success = $this->applyAction($args);
            if (!$success) {
                // the workflow operation failed
                return false;
            }
    
            if ($entity->supportsHookSubscribers()) {
                // Let any hooks know that we have created, updated or deleted an item
                $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
                $url = null;
                if ($action != 'delete') {
                    $urlArgs = $entity->createUrlArgs();
                    $urlArgs['_locale'] = $this->request->getLocale();
                    $url = new RouteUrl('rkhelpermodule_' . $this->objectType . '_display', $urlArgs);
                }
                $this->hookHelper->callProcessHooks($entity, $hookType, $url);
            }
        }
    
        if (true === $this->hasPageLockSupport && $this->templateParameters['mode'] == 'edit' && $this->kernel->isBundle('ZikulaPageLockModule') && null !== $this->lockingApi) {
            $lockName = 'RKHelperModule' . $this->objectTypeCapital . $this->createCompositeIdentifier();
            $this->lockingApi->releaseLock($lockName);
        }
    
        return new RedirectResponse($this->getRedirectUrl($args), 302);
    }
    
    /**
     * Prepare update of translations.
     */
    protected function processTranslationsForUpdate()
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $entityTransClass = '\\RK\\HelperModule\\Entity\\' . ucfirst($this->objectType) . 'TranslationEntity';
        $transRepository = $this->entityFactory->getObjectManager()->getRepository($entityTransClass);
    
        // persist translated fields
        $translations = $this->translatableHelper->processEntityAfterEditing($this->objectType, $entity, $this->form);
    
        if ($this->variableApi->getSystemVar('multilingual') == 1) {
            foreach ($translations as $locale => $translationFields) {
                foreach ($translationFields as $fieldName => $value) {
                    $transRepository->translate($entity, $fieldName, $locale, $value);
                }
            }
        }
    
        $this->entityFactory->getObjectManager()->flush();
    }
    
    /**
     * Get success or error message for default operations.
     *
     * @param array   $args    arguments from handleCommand method
     * @param Boolean $success true if this is a success, false for default error
     *
     * @return String desired status or error message
     */
    protected function getDefaultMessage($args, $success = false)
    {
        $message = '';
        switch ($args['commandName']) {
            case 'create':
                if (true === $success) {
                    $message = $this->__('Done! Item created.');
                } else {
                    $message = $this->__('Error! Creation attempt failed.');
                }
                break;
            case 'update':
                if (true === $success) {
                    $message = $this->__('Done! Item updated.');
                } else {
                    $message = $this->__('Error! Update attempt failed.');
                }
                break;
            case 'delete':
                if (true === $success) {
                    $message = $this->__('Done! Item deleted.');
                } else {
                    $message = $this->__('Error! Deletion attempt failed.');
                }
                break;
        }
    
        return $message;
    }
    
    /**
     * Add success or error message to session.
     *
     * @param array   $args    arguments from handleCommand method
     * @param Boolean $success true if this is a success, false for default error
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    protected function addDefaultMessage($args, $success = false)
    {
        $message = $this->getDefaultMessage($args, $success);
        if (empty($message)) {
            return;
        }
    
        $flashType = true === $success ? 'status' : 'error';
        $this->request->getSession()->getFlashBag()->add($flashType, $message);
        $logArgs = ['app' => 'RKHelperModule', 'user' => $this->currentUserApi->get('uname'), 'entity' => $this->objectType, 'id' => $this->entityRef->createCompositeIdentifier()];
        if (true === $success) {
            $this->logger->notice('{app}: User {user} updated the {entity} with id {id}.', $logArgs);
        } else {
            $this->logger->error('{app}: User {user} tried to update the {entity} with id {id}, but failed.', $logArgs);
        }
    }

    /**
     * Input data processing called by handleCommand method.
     *
     * @param array $args Additional arguments
     */
    public function fetchInputData($args)
    {
        // fetch posted data input values as an associative array
        $formData = $this->form->getData();
    
        if ($this->templateParameters['mode'] == 'create' && isset($this->form['repeatCreation']) && $this->form['repeatCreation']->getData() == 1) {
            $this->repeatCreateAction = true;
        }
    
        if (method_exists($this->entityRef, 'getCreatedBy')) {
            if (isset($this->form['moderationSpecificCreator']) && null !== $this->form['moderationSpecificCreator']->getData()) {
                $this->entityRef->setCreatedBy($this->form['moderationSpecificCreator']->getData());
            }
            if (isset($this->form['moderationSpecificCreationDate']) && $this->form['moderationSpecificCreationDate']->getData() != '') {
                $this->entityRef->setCreatedDate($this->form['moderationSpecificCreationDate']->getData());
            }
        }
    
        if (isset($this->form['additionalNotificationRemarks']) && $this->form['additionalNotificationRemarks']->getData() != '') {
            $this->request->getSession()->set('RKHelperModuleAdditionalNotificationRemarks', $this->form['additionalNotificationRemarks']->getData());
        }
    
        // return remaining form data
        return $formData;
    }

    /**
     * This method executes a certain workflow action.
     *
     * @param array $args Arguments from handleCommand method
     *
     * @return bool Whether everything worked well or not
     */
    public function applyAction(array $args = [])
    {
        // stub for subclasses
        return false;
    }

    /**
     * Sets optional locking api reference.
     *
     * @param LockingApi $lockingApi
     */
    public function setLockingApi(LockingApi $lockingApi)
    {
        $this->lockingApi = $lockingApi;
    }
}
