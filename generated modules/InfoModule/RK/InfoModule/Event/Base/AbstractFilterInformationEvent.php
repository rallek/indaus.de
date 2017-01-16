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

namespace RK\InfoModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use RK\InfoModule\Entity\InformationEntity;

/**
 * Event base class for filtering information processing.
 */
class AbstractFilterInformationEvent extends Event
{
    /**
     * @var InformationEntity Reference to treated entity instance.
     */
    protected $information;

    public function __construct(InformationEntity $information)
    {
        $this->information = $information;
    }

    public function getInformation()
    {
        return $this->information;
    }
}