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

namespace RK\ReferenceModule\Base {

    use Zikula\Core\AbstractModule;
    
    /**
     * Module base class.
     */
    abstract class AbstractRKReferenceModule extends AbstractModule
    {
    }
}

namespace {

    if (!class_exists('Content_AbstractContentType')) {
        if (file_exists('modules/Content/lib/Content/AbstractContentType.php')) {
            require_once 'modules/Content/lib/Content/AbstractType.php';
            require_once 'modules/Content/lib/Content/AbstractContentType.php';
        } else {
            class Content_AbstractContentType {}
        }
    }

    class RKReferenceModule_ContentType_ItemList extends \RK\ReferenceModule\ContentType\ItemList {
    }
    class RKReferenceModule_ContentType_Item extends \RK\ReferenceModule\ContentType\Item {
    }
}
