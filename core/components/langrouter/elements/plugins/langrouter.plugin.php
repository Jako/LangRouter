<?php
/**
 * LangRouter
 *
 * @package langrouter
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = $modx->getOption('langrouter.core_path', null, $modx->getOption('core_path') . 'components/langrouter/');
/** @var LangRouter $langrouter */
$langrouter = $modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', array(
    'core_path' => $corePath
));

$className = 'LangRouter' . $modx->event->name;
$modx->loadClass('LangRouterPlugin', $langrouter->getOption('modelPath') . 'langrouter/events/', true, true);
$modx->loadClass($className, $langrouter->getOption('modelPath') . 'langrouter/events/', true, true);
if (class_exists($className)) {
    /** @var LangRouterPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;