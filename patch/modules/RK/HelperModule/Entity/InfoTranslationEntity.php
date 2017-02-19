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

use RK\HelperModule\Entity\Base\AbstractInfoTranslationEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity extension domain class storing info translations.
 *
 * This is the concrete translation class for info entities.
 *
 * @ORM\Entity(repositoryClass="RK\HelperModule\Entity\Repository\InfoTranslationRepository")
 * @ORM\Table(name="rk_helper_info_translation",
 *     indexes={
 *         @ORM\Index(name="translations_lookup_idx", columns={
 *             "locale", "object_class", "foreign_key"
 *         })
 *     }
 * )
 */
class InfoTranslationEntity extends BaseEntity
{
    // feel free to add your own methods here
}
