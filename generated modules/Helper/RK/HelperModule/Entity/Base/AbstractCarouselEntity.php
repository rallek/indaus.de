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
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use RK\HelperModule\Traits\EntityWorkflowTrait;
use RK\HelperModule\Traits\StandardFieldsTrait;

use RuntimeException;
use ServiceUtil;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for carousel entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractCarouselEntity extends EntityAccess
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
    protected $_objectType = 'carousel';
    
    /**
     * @Assert\Type(type="bool")
     * @var boolean Option to bypass validation if needed
     */
    protected $_bypassValidation = false;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
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
     * @var string $carouselName
     */
    protected $carouselName = '';
    
    /**
     * make a note for which usecase you create this carousel
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $remarks
     */
    protected $remarks = '';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $slidingTime
     */
    protected $slidingTime = 5000;
    
    /**
     * check if controlls should be shown
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $controls
     */
    protected $controls = true;
    
    /**
     * This field is for filtering in the block settings. So it makes it possible to have more than one carousel managed.
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @var string $carouselGroup
     */
    protected $carouselGroup = '';
    
    
    /**
     * Bidirectional - One carousel [carousel] has many carouselItems [carousel items] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="RK\HelperModule\Entity\CarouselItemEntity", mappedBy="carousel")
     * @ORM\JoinTable(name="rk_helper_carouselcarouselitems")
     * @var \RK\HelperModule\Entity\CarouselItemEntity[] $carouselItems
     */
    protected $carouselItems = null;
    
    
    /**
     * CarouselEntity constructor.
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
        $this->carouselItems = new ArrayCollection();
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
     * Returns the carousel name.
     *
     * @return string
     */
    public function getCarouselName()
    {
        return $this->carouselName;
    }
    
    /**
     * Sets the carousel name.
     *
     * @param string $carouselName
     *
     * @return void
     */
    public function setCarouselName($carouselName)
    {
        $this->carouselName = isset($carouselName) ? $carouselName : '';
    }
    
    /**
     * Returns the remarks.
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }
    
    /**
     * Sets the remarks.
     *
     * @param string $remarks
     *
     * @return void
     */
    public function setRemarks($remarks)
    {
        $this->remarks = isset($remarks) ? $remarks : '';
    }
    
    /**
     * Returns the sliding time.
     *
     * @return integer
     */
    public function getSlidingTime()
    {
        return $this->slidingTime;
    }
    
    /**
     * Sets the sliding time.
     *
     * @param integer $slidingTime
     *
     * @return void
     */
    public function setSlidingTime($slidingTime)
    {
        $this->slidingTime = intval($slidingTime);
    }
    
    /**
     * Returns the controls.
     *
     * @return boolean
     */
    public function getControls()
    {
        return $this->controls;
    }
    
    /**
     * Sets the controls.
     *
     * @param boolean $controls
     *
     * @return void
     */
    public function setControls($controls)
    {
        if ($controls !== $this->controls) {
            $this->controls = (bool)$controls;
        }
    }
    
    /**
     * Returns the carousel group.
     *
     * @return string
     */
    public function getCarouselGroup()
    {
        return $this->carouselGroup;
    }
    
    /**
     * Sets the carousel group.
     *
     * @param string $carouselGroup
     *
     * @return void
     */
    public function setCarouselGroup($carouselGroup)
    {
        $this->carouselGroup = isset($carouselGroup) ? $carouselGroup : '';
    }
    
    
    /**
     * Returns the carousel items.
     *
     * @return \RK\HelperModule\Entity\CarouselItemEntity[]
     */
    public function getCarouselItems()
    {
        return $this->carouselItems;
    }
    
    /**
     * Sets the carousel items.
     *
     * @param \RK\HelperModule\Entity\CarouselItemEntity[] $carouselItems
     *
     * @return void
     */
    public function setCarouselItems($carouselItems)
    {
        foreach ($carouselItems as $carouselItemSingle) {
            $this->addCarouselItems($carouselItemSingle);
        }
    }
    
    /**
     * Adds an instance of \RK\HelperModule\Entity\CarouselItemEntity to the list of carousel items.
     *
     * @param \RK\HelperModule\Entity\CarouselItemEntity $carouselItem The instance to be added to the collection
     *
     * @return void
     */
    public function addCarouselItems(\RK\HelperModule\Entity\CarouselItemEntity $carouselItem)
    {
        $this->carouselItems->add($carouselItem);
        $carouselItem->setCarousel($this);
    }
    
    /**
     * Removes an instance of \RK\HelperModule\Entity\CarouselItemEntity from the list of carousel items.
     *
     * @param \RK\HelperModule\Entity\CarouselItemEntity $carouselItem The instance to be removed from the collection
     *
     * @return void
     */
    public function removeCarouselItems(\RK\HelperModule\Entity\CarouselItemEntity $carouselItem)
    {
        $this->carouselItems->removeElement($carouselItem);
        $carouselItem->setCarousel(null);
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
                . $this->getCarouselName();
    
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
        $listEntries = $helper->getWorkflowStateEntriesForCarousel();
    
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
        return 'rkhelpermodule.ui_hooks.carousells';
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
        return 'Carousel ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
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
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
