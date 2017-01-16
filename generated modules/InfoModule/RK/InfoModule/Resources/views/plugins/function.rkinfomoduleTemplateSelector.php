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
 * The rkinfomoduleTemplateSelector plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params All attributes passed to this function from the template
 * @param  Zikula_Form_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_rkinfomoduleTemplateSelector($params, $view)
{
    $result = [];

    $result[] = ['text' => $this->__('Only item titles'), 'value' => 'itemlist_display.html.twig'];
    $result[] = ['text' => $this->__('With description'), 'value' => 'itemlist_display_description.html.twig'];
    $result[] = ['text' => $this->__('Custom template'), 'value' => 'custom'];

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);

        return;
    }

    return $result;
}