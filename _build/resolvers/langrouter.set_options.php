<?php
/**
 * Setup options
 *
 * @package langrouter
 * @subpackage build
 *
 * @var mixed $object
 * @var array $options
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx = &$object->xpdo;

            $setting = $modx->getObject('modSystemSetting', 'babel.contextDefault');
            if (!$setting) {
                $setting = $modx->newObject('modSystemSetting');
                $setting->set('key', 'babel.contextDefault');
            }
            $setting->set('area', 'system');

            $contextDefault = $modx->getOption('contextDefault', $options, false);
            if ($contextDefault) {
                $modx->log(xPDO::LOG_LEVEL_INFO, 'Set system setting "babel.contextDefault" to "' . $contextDefault . '"');

                $babelNamespace = $modx->getObject('modNamespace', 'babel');
                if (!$babelNamespace) {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Babel has to be installed before LangRouter to create the settings right. Please install Babel and reinstall LangRouter after.');
                } else {
                    $setting->addOne($babelNamespace);
                }

                $setting->set('value', $contextDefault);
            }
            $setting->save();
            break;
    }
}
return true;
