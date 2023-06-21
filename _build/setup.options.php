<?php
/**
 * Setup options
 *
 * @package langrouter
 * @subpackage build
 *
 * @var modX $modx
 * @var array $options
 */

$output = '<style type="text/css">
    #modx-setupoptions-panel { display: none; }
    #modx-setupoptions-form p { margin-bottom: 10px; }
    #modx-setupoptions-form h2 { margin-bottom: 15px; }
</style>';

$values = [];
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.contextDefault']);
        $values['contextDefault'] = ($setting) ? $setting->get('value') : '';
        unset($setting);

        $output .= '<h2>Install LangRouter</h2>
        <p>Thanks for installing LangRouter. This open source extra was
        developed by Treehill Studio - MODX development in Münsterland.</p>

        <p>During the installation, we will collect some statistical data (the
        hostname, the IP address, the PHP version and the MODX version of your
        MODX installation). Your data will be kept confidential and under no
        circumstances be used for promotional purposes or disclosed to third
        parties. We only like to know the usage count of this package.</p>
        
        <p>If you install this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>
        
        <hr>
        
        <p>Please enter the Babel Default Context, which should be served when
        no language is specified in request, eg. \'en\'.<br><br></p>';

        $output .= '<div style="position: relative">
                        <label for="contextDefault">Babel Default Context:</label>
                        <input type="text" name="contextDefault" id="contextDefault" width="450" value="' . $values['contextDefault'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                    </div>';
        break;
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting', ['key' => 'babel.contextDefault']);
        $values['contextDefault'] = ($setting) ? $setting->get('value') : '';
        unset($setting);

        $output .= '<h2>Upgrade LangRouter</h2>

        <p>LangRouter will be upgraded. This open source extra was developed by
        Treehill Studio - MODX development in Münsterland.</p>

        During the upgrade, we will collect some statistical data (the hostname,
        the IP address, the PHP version, the MODX version of your MODX
        installation and the previous installed version of this extra package).
        Your data will be kept confidential and under no circumstances be used
        for promotional purposes or disclosed to third parties. We only like to
        know the usage count of this package.</p>

        <p>If you upgrade this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>';

        $output .= '<div style="position: relative">
                        <label for="contextDefault">Babel Default Context:</label>
                        <input type="text" name="contextDefault" id="contextDefault" width="450" value="' . $values['contextDefault'] . '" style="font-size: 13px; padding: 5px; width: calc(100% - 10px); height: 32px; margin-bottom: 10px" />
                    </div>';
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return $output;
