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

namespace RK\ReferenceModule\Entity;

use RK\ReferenceModule\Entity\Base\AbstractActivityEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use RK\ReferenceModule\Traits\EntityWorkflowTrait;
use RK\ReferenceModule\Traits\StandardFieldsTrait;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for activity entities.
 * @ORM\Entity(repositoryClass="RK\ReferenceModule\Entity\Repository\ActivityRepository")
 * @ORM\Table(name="rk_refere_activity",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class ActivityEntity extends BaseEntity
{
    // feel free to add your own methods here
}
