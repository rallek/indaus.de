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

namespace RK\InfoModule\Entity\Factory\Base;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

/**
 * Factory class used to create entities and receive entity repositories.
 *
 * This is the base factory class.
 */
abstract class AbstractInfoFactory
{
    /**
     * @var ObjectManager The object manager to be used for determining the repository
     */
    protected $objectManager;

    /**
     * InfoFactory constructor.
     *
     * @param ObjectManager $objectManager The object manager to be used for determining the repositories
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns a repository for a given object type.
     *
     * @param string $objectType Name of desired entity type
     *
     * @return EntityRepository The repository responsible for the given object type
     */
    public function getRepository($objectType)
    {
        $entityClass = 'RK\\InfoModule\\Entity\\' . ucfirst($objectType) . 'Entity';

        return $this->objectManager->getRepository($entityClass);
    }

    /**
     * Creates a new information instance.
     *
     * @return RK\InfoModule\Entity\informationEntity The newly created entity instance
     */
    public function createInformation()
    {
        $entityClass = 'RK\\InfoModule\\Entity\\InformationEntity';

        return new $entityClass();
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Sets the object manager.
     *
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
}
