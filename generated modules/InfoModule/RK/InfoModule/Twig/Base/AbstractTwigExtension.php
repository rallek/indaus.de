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

namespace RK\InfoModule\Twig\Base;

use Twig_Extension;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\VariableApi;
use RK\InfoModule\Helper\ListEntriesHelper;
use RK\InfoModule\Helper\WorkflowHelper;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends Twig_Extension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApi
     */
    protected $variableApi;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    /**
     * TwigExtension constructor.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param VariableApi         $variableApi    VariableApi service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     * @param ListEntriesHelper   $listHelper     ListEntriesHelper service instance
     */
    public function __construct(TranslatorInterface $translator, VariableApi $variableApi, WorkflowHelper $workflowHelper, ListEntriesHelper $listHelper)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
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
     * Returns a list of custom Twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('rkinfomodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new \Twig_SimpleFunction('rkinfomodule_templateSelector', [$this, 'getTemplateSelector']),
            new \Twig_SimpleFunction('rkinfomodule_userAvatar', [$this, 'getUserAvatar'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * Returns a list of custom Twig filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('rkinfomodule_listEntry', [$this, 'getListEntry']),
            new \Twig_SimpleFilter('rkinfomodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * The rkinfomodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|rkinfomodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|rkinfomodule_objectState(false) }} {# no ui feedback #}
     *
     * @param string  $state      Name of given workflow state
     * @param boolean $uiFeedback Whether the output should include some visual feedback about the state
     *
     * @return string Enriched and translated workflow state ready for display
     */
    public function getObjectState($state = 'initial', $uiFeedback = true)
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if (true === $uiFeedback) {
            $result = '<span class="label label-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    
    /**
     * The rkinfomodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|rkinfomodule_listEntry('entityName', 'fieldName') }}
     *
     * @param string $value      The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     * @param string $delimiter  String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function getListEntry($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && $value != '0') || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    /**
     * The rkinfomodule_objectTypeSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getObjectTypeSelector()
    {
        $result = [];
    
        $result[] = ['text' => $this->__('Informations'), 'value' => 'information'];
    
        return $result;
    }
    
    
    /**
     * The rkinfomodule_templateSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getTemplateSelector()
    {
        $result = [];
    
        $result[] = ['text' => $this->__('Only item titles'), 'value' => 'itemlist_display.html.twig'];
        $result[] = ['text' => $this->__('With description'), 'value' => 'itemlist_display_description.html.twig'];
        $result[] = ['text' => $this->__('Custom template'), 'value' => 'custom'];
    
        return $result;
    }
    
    /**
     * Display the avatar of a user.
     *
     * @param int    $uid    The user's id
     * @param int    $width  Image width (optional)
     * @param int    $height Image height (optional)
     * @param int    $size   Gravatar size (optional)
     * @param string $rating Gravatar self-rating [g|pg|r|x] see: http://en.gravatar.com/site/implement/images/ (optional)
     *
     * @return string
     */
    public function getUserAvatar($uid = 0, $width = 0, $height = 0, $size = 0, $rating = '')
    {
        $params = ['uid' => $uid];
        if ($width > 0) {
            $params['width'] = $width;
        }
        if ($height > 0) {
            $params['height'] = $height;
        }
        if ($size > 0) {
            $params['size'] = $size;
        }
        if ($rating != '') {
            $params['rating'] = $rating;
        }
    
        include_once 'lib/legacy/viewplugins/function.useravatar.php';
    
        $view = \Zikula_View::getInstance('RKInfoModule');
        $result = smarty_function_useravatar($params, $view);
    
        return $result;
    }
}