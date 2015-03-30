<?php
/**
 * Build the setup options form.
 *
 * @package langrouter
 * @subpackage build
 */
/* default values */
$values = array(
    'contextDefault' => 'web'
);

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting', array('key' => 'babel.contextDefault'));
        if ($setting != null) {
            $values['contextDefault'] = $setting->get('value');
        }
        unset($setting);
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output = <<<HTML
<p>Please enter the Babel Default Context, which should be served when no language is specified in request, eg. 'pl'.<br/><br/></p>
<label for="babel-contextDefault">Babel Default Context:</label>
<input type="text" name="contextDefault" id="babel-contextDefault" width="300" value="{$values['contextDefault']}" />
HTML;

return $output;