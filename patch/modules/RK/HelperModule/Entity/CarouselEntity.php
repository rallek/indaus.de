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

namespace RK\HelperModule\Entity;

use RK\HelperModule\Entity\Base\AbstractCarouselEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use RK\HelperModule\Traits\EntityWorkflowTrait;
use RK\HelperModule\Traits\StandardFieldsTrait;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for carousel entities.
 * @ORM\Entity(repositoryClass="RK\HelperModule\Entity\Repository\CarouselRepository")
 * @ORM\Table(name="rk_helper_carousel",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class CarouselEntity extends BaseEntity
{
    // feel free to add your own methods here
}
