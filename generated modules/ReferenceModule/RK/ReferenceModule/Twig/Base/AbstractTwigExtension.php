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

namespace RK\ReferenceModule\Twig\Base;

use Twig_Extension;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\VariableApi;
use RK\ReferenceModule\Helper\ListEntriesHelper;
use RK\ReferenceModule\Helper\WorkflowHelper;

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
            new \Twig_SimpleFunction('rkreferencemodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new \Twig_SimpleFunction('rkreferencemodule_templateSelector', [$this, 'getTemplateSelector']),
            new \Twig_SimpleFunction('rkreferencemodule_userAvatar', [$this, 'getUserAvatar'], ['is_safe' => ['html']])
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
            new \Twig_SimpleFilter('rkreferencemodule_fileSize', [$this, 'getFileSize'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('rkreferencemodule_listEntry', [$this, 'getListEntry']),
            new \Twig_SimpleFilter('rkreferencemodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * The rkreferencemodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|rkreferencemodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|rkreferencemodule_objectState(false) }} {# no ui feedback #}
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
     * The rkreferencemodule_fileSize filter displays the size of a given file in a readable way.
     * Example:
     *     {{ 12345|rkreferencemodule_fileSize }}
     *
     * @param integer $size     File size in bytes
     * @param string  $filepath The input file path including file name (if file size is not known)
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    public function getFileSize($size = 0, $filepath = '', $nodesc = false, $onlydesc = false)
    {
        if (!is_numeric($size)) {
            $size = (int) $size;
        }
        if (!$size) {
            if (empty($filepath) || !file_exists($filepath)) {
                return '';
            }
            $size = filesize($filepath);
        }
        if (!$size) {
            return '';
        }
    
        return $this->getReadableFileSize($size, $nodesc, $onlydesc);
    }
    
    /**
     * Display a given file size in a readable format
     *
     * @param string  $size     File size in bytes
     * @param boolean $nodesc   If set to true the description will not be appended
     * @param boolean $onlydesc If set to true only the description will be returned
     *
     * @return string File size in a readable form
     */
    private function getReadableFileSize($size, $nodesc = false, $onlydesc = false)
    {
        $sizeDesc = $this->__('Bytes');
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('KB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('MB');
        }
        if ($size >= 1024) {
            $size /= 1024;
            $sizeDesc = $this->__('GB');
        }
        $sizeDesc = '&nbsp;' . $sizeDesc;
    
        // format number
        $dec_point = ',';
        $thousands_separator = '.';
        if ($size - number_format($size, 0) >= 0.005) {
            $size = number_format($size, 2, $dec_point, $thousands_separator);
        } else {
            $size = number_format($size, 0, '', $thousands_separator);
        }
    
        // append size descriptor if desired
        if (!$nodesc) {
            $size .= $sizeDesc;
        }
    
        // return either only the description or the complete string
        $result = ($onlydesc) ? $sizeDesc : $size;
    
        return $result;
    }
    
    
    /**
     * The rkreferencemodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|rkreferencemodule_listEntry('entityName', 'fieldName') }}
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
     * The rkreferencemodule_objectTypeSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getObjectTypeSelector()
    {
        $result = [];
    
        $result[] = ['text' => $this->__('Attached images'), 'value' => 'attachedImage'];
        $result[] = ['text' => $this->__('Activities'), 'value' => 'activity'];
    
        return $result;
    }
    
    
    /**
     * The rkreferencemodule_templateSelector function provides items for a dropdown selector.
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
    
        $view = \Zikula_View::getInstance('RKReferenceModule');
        $result = smarty_function_useravatar($params, $view);
    
        return $result;
    }
}
