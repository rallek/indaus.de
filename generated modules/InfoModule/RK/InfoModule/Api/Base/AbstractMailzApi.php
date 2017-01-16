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

namespace RK\InfoModule\Api\Base;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Zikula_AbstractBase;

/**
 * Mailz api base class.
 */
abstract class AbstractMailzApi extends Zikula_AbstractBase implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * MailzApi constructor.
     */
    public function __construct()
    {
        $this->setContainer(\ServiceUtil::getManager());
    }

    /**
     * Returns existing Mailz plugins with type / title.
     *
     * @param array $args List of arguments
     *
     * @return array List of provided plugin functions
     */
    public function getPlugins(array $args = [])
    {
        $translator = $this->container->get('translator.default');
    
        $plugins = [];
        $plugins[] = [
            'pluginid'      => 1,
            'module'        => 'RKInfoModule',
            'title'         => $translator->__('3 newest informations'),
            'description'   => $translator->__('A list of the three newest informations.')
        ];
        $plugins[] = [
            'pluginid'      => 2,
            'module'        => 'RKInfoModule',
            'title'         => $translator->__('3 random informations'),
            'description'   => $translator->__('A list of three random informations.')
        ];
    
        return $plugins;
    }
    
    /**
     * Returns the content for a given Mailz plugin.
     *
     * @param array    $args                List of arguments
     * @param int      $args['pluginid']    id number of plugin (internal id for this module, see getPlugins method)
     * @param string   $args['params']      optional, show specific one or all otherwise
     * @param int      $args['uid']         optional, user id for user specific content
     * @param string   $args['contenttype'] h or t for html or text
     * @param datetime $args['last']        timestamp of last newsletter
     *
     * @return string output of plugin template
     */
    public function getContent(array $args = [])
    {
        // $args is something like:
        // Array ( [uid] => 5 [contenttype] => h [pluginid] => 1 [nid] => 1 [last] => 0000-00-00 00:00:00 [params] => Array ( [] => ) ) 1
        $objectType = 'information';
    
        $repository = $this->container->get('rk_info_module.entity_factory')->getRepository($objectType);
    
        $selectionHelper = $this->container->get('rk_info_module.selection_helper');
        $idFields = $selectionHelper->getIdFields($objectType);
    
        $sortParam = '';
        if ($args['pluginid'] == 2) {
            $sortParam = 'RAND()';
        } elseif ($args['pluginid'] == 1) {
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' ASC';
                }
            }
        }
    
        $where = ''/*$this->filter*/;
        $resultsPerPage = 3;
    
        // get objects from database
        list($entities, $objectCount) = $selectionHelper->getEntitiesPaginated($objectType, $where, $orderBy, 1, $resultsPerPage);
    
        $templateType = $args['contenttype'] == 't' ? 'text' : 'html';
    
        //$templateParameters = ['sorting' => $this->sorting, 'amount' => $this->amount, 'filter' => $this->filter, 'template' => $this->template];
        $templateParameters = [
            'objectType' => $objectType,
            'items' => $entities
        ];
        $templateParameters = array_merge($templateParameters, $repository->getAdditionalTemplateParameters('api', ['name' => 'mailz']));
    
        return $this->container->get('twig')->render(
            '@RKInfoModule/Mailz/itemlist_information.' . $templateType . '.twig',
            $templateParameters
        );
    }
}