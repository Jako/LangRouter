<?php
/**
 * LangRouter Plugin
 *
 * @package langrouter
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\LangRouter\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('langrouter.core_path', null, $modx->getOption('core_path') . 'components/langrouter/');
/** @var LangRouter $langrouter */
$langrouter = $modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', array(
    'core_path' => $corePath
));

if ($langrouter) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'LangRouter Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'LangRouter Plugin');
    }
}

return;