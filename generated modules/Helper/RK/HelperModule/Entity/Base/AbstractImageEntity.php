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

namespace RK\HelperModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use RK\HelperModule\Traits\EntityWorkflowTrait;
use RK\HelperModule\Traits\StandardFieldsTrait;

use RuntimeException;
use ServiceUtil;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for image entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractImageEntity extends EntityAccess
{
    /**
     * Hook entity workflow field and behaviour.
     */
    use EntityWorkflowTrait;

    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'image';
    
    /**
     * @Assert\Type(type="bool")
     * @var boolean Option to bypass validation if needed
     */
    protected $_bypassValidation = false;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getWorkflowStateAllowedValues", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $imageTitle
     */
    protected $imageTitle = '';
    
    /**
     * My image meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $myImageMeta
     */
    protected $myImageMeta = [];
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
        mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
     * )
     * @var string $myImage
     */
    protected $myImage = null;
    
    /**
     * Full my image path as url.
     *
     * @Assert\Type(type="string")
     * @Assert\Url()
     * @var string $myImageUrl
     */
    protected $myImageUrl = '';
    
    /**
     * please note if the image do have a specific reason
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="2000")
     * @var text $myDescription
     */
    protected $myDescription = '';
    
    
    
    /**
     * ImageEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->initWorkflow();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        $this->_objectType = $_objectType;
    }
    
    /**
     * Returns the _bypass validation.
     *
     * @return boolean
     */
    public function get_bypassValidation()
    {
        return $this->_bypassValidation;
    }
    
    /**
     * Sets the _bypass validation.
     *
     * @param boolean $_bypassValidation
     *
     * @return void
     */
    public function set_bypassValidation($_bypassValidation)
    {
        $this->_bypassValidation = $_bypassValidation;
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        $this->workflowState = isset($workflowState) ? $workflowState : '';
    }
    
    /**
     * Returns the image title.
     *
     * @return string
     */
    public function getImageTitle()
    {
        return $this->imageTitle;
    }
    
    /**
     * Sets the image title.
     *
     * @param string $imageTitle
     *
     * @return void
     */
    public function setImageTitle($imageTitle)
    {
        $this->imageTitle = isset($imageTitle) ? $imageTitle : '';
    }
    
    /**
     * Returns the my image.
     *
     * @return string
     */
    public function getMyImage()
    {
        return $this->myImage;
    }
    
    /**
     * Sets the my image.
     *
     * @param string $myImage
     *
     * @return void
     */
    public function setMyImage($myImage)
    {
        $this->myImage = isset($myImage) ? $myImage : '';
    }
    
    /**
     * Returns the my image url.
     *
     * @return string
     */
    public function getMyImageUrl()
    {
        return $this->myImageUrl;
    }
    
    /**
     * Sets the my image url.
     *
     * @param string $myImageUrl
     *
     * @return void
     */
    public function setMyImageUrl($myImageUrl)
    {
        $this->myImageUrl = isset($myImageUrl) ? $myImageUrl : '';
    }
    
    /**
     * Returns the my image meta.
     *
     * @return array
     */
    public function getMyImageMeta()
    {
        return $this->myImageMeta;
    }
    
    /**
     * Sets the my image meta.
     *
     * @param array $myImageMeta
     *
     * @return void
     */
    public function setMyImageMeta($myImageMeta = [])
    {
        $this->myImageMeta = isset($myImageMeta) ? $myImageMeta : '';
    }
    
    /**
     * Returns the my description.
     *
     * @return text
     */
    public function getMyDescription()
    {
        return $this->myDescription;
    }
    
    /**
     * Sets the my description.
     *
     * @param text $myDescription
     *
     * @return void
     */
    public function setMyDescription($myDescription)
    {
        $this->myDescription = isset($myDescription) ? $myDescription : '';
    }
    
    
    
    
    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     *
     * @return string The display title
     */
    public function getTitleFromDisplayPattern()
    {
        $listHelper = ServiceUtil::get('rk_helper_module.listentries_helper');
    
        $formattedTitle = ''
                . $this->getImageTitle();
    
        return $formattedTitle;
    }
    
    
    /**
     * Returns a list of possible choices for the workflowState list field.
     * This method is used for validation.
     *
     * @return array List of allowed choices
     */
    public static function getWorkflowStateAllowedValues()
    {
        $container = ServiceUtil::get('service_container');
        $helper = $container->get('rk_helper_module.listentries_helper');
        $listEntries = $helper->getWorkflowStateEntriesForImage();
    
        $allowedValues = ['initial'];
        foreach ($listEntries as $entry) {
            $allowedValues[] = $entry['value'];
        }
    
        return $allowedValues;
    }
    
    /**
     * Start validation and raise exception if invalid data is found.
     *
     * @return boolean Whether everything is valid or not
     */
    public function validate()
    {
        if (true === $this->_bypassValidation) {
            return true;
        }
    
        $validator = ServiceUtil::get('validator');
        $errors = $validator->validate($this);
    
        if (count($errors) > 0) {
            $flashBag = ServiceUtil::get('session')->getFlashBag();
            foreach ($errors as $error) {
                $flashBag->add('error', $error->getMessage());
            }
    
            return false;
        }
    
        return true;
    }
    
    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        $args = [];
    
        $args['id'] = $this['id'];
    
        if (property_exists($this, 'slug')) {
            $args['slug'] = $this['slug'];
        }
    
        return $args;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers
     */
    public function createCompositeIdentifier()
    {
        $itemId = $this['id'];
    
        return $itemId;
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'rkhelpermodule.ui_hooks.images';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Image ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!($this->id)) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifiers
        $this->setId(0);
    
        // reset workflow
        $this->resetWorkflow();
    
        // reset upload fields
        $this->setMyImage(null);
        $this->setMyImageMeta([]);
        $this->setMyImageUrl('');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
