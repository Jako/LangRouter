<?php
/**
 * Set install options.
 *
 * @package langrouter
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx =& $object->xpdo;

            $contextDefault = $modx->getOption('contextDefault', $options, false);
            if ($contextDefault) {
                $modx->log(xPDO::LOG_LEVEL_INFO, 'Set system setting "babel.contextDefault" to "' . $contextDefault . '"');
                $setting = $modx->getObject('modSystemSetting', 'babel.contextDefault');
                if (!$setting) {
                    $setting = $modx->newObject('modSystemSetting', array('key', 'babel.contextDefault'));
                }
                $setting->set('value', $contextDefault);
                $setting->set('namespace', 'babel');
                $setting->set('area', 'common');
                $setting->save();
            }
            break;
    }
}
return true;