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

/**
 * RKInfoModule information needle information.
 *
 * @param none
 *
 * @return string with short usage description
 */
function RKInfoModule_needleapi_information_baseInfo()
{
    $info = [
        // module name
        'module'  => 'RKInfoModule',
        // possible needles
        'info'    => 'INFO{INFORMATIONS|INFORMATION-informationId}',
        // whether a reverse lookup is possible, needs RKInfoModule_needleapi_information_inspect() function
        'inspect' => false
    ];

    return $info;
}
