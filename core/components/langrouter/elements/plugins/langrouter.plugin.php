<?php
/**
 * LangRouter
 *
 * @package langrouter
 * @subpackage plugin
 *
 * @event OnHandleRequest
 * @event OnContextSave
 * @event OnContextRemove
 * @event OnSiteRefresh
 *
 * @var modx $modx
 */

switch ($modx->event->name) {
    case 'OnHandleRequest':

        if ($modx->context->get('key') != "mgr") {
            // Init LangRouter service class
            $corePath = $modx->getOption('langrouter.core_path', null, $modx->getOption('core_path') . 'components/langrouter/');
            $langrouter = $modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', array(
                'core_path' => $corePath
            ));

            // Log start
            $langrouter->logRequest('Unhandled request');

            // Get contexts and their cultureKeys
            $contextmap = $modx->cacheManager->get($langrouter->getOption('cacheKey'), $langrouter->getOption('cacheOptions'));
            if (empty($contextmap)) {
                $babelContexts = explode(',', $modx->getOption('babel.contextKeys'));
                $contextmap = $langrouter->contextmap($babelContexts);
                $modx->cacheManager->set($langrouter->getOption('cacheKey'), $contextmap, 0, $langrouter->getOption('cacheOptions'));
            }
            $langrouter->logDump($contextmap, 'contextmap');

            // Determine language from request
            $queryKey = $modx->getOption('request_param_alias', null, 'q');
            $query = (isset($_REQUEST[$queryKey])) ? $_REQUEST[$queryKey] : '';
            $cultureKey = substr($query, 0, strpos($query, '/'));

            if ($cultureKey || $query === '') {
                // Serve the proper context and language
                if (array_key_exists(strtolower($cultureKey), array_change_key_case($contextmap))) {
                    // Culture key is found, so switch the context
                    $modx->switchContext($contextmap[$cultureKey]);

                    // Remove cultureKey from request
                    $_REQUEST[$queryKey] = substr($query, strlen($cultureKey) + 1);

                    // Log found
                    $langrouter->logRequest('Culture key found in URI');

                    // Set culture key
                    $modx->cultureKey = $cultureKey;

                    // Set locale since $modx->_initCulture is called before OnHandleRequest
                    if ($modx->getOption('setlocale', null, true)) {
                        $locale = setlocale(LC_ALL, null);
                        setlocale(LC_ALL, $modx->getOption('locale', null, $locale, true));
                    }
                } else {
                    // Culture key is has to be detected
                    $clientCultureKey = array_flip(array_intersect_key($langrouter->clientLangDetect(), $contextmap));
                    if ($clientCultureKey) {
                        // Use first entry of detected client culture key
                        $cultureKey = current($clientCultureKey);
                        // Log detected
                        $langrouter->logDump($cultureKey, 'Detected culture key');
                    } else {
                        // Use default context key
                        $cultureKey = trim($modx->getOption('babel.contextDefault', null, 'web'));
                        // Log default
                        $langrouter->logDump($cultureKey, 'Default culture key');
                    }

                    // Switch the context
                    $switched = $modx->switchContext($contextmap[$cultureKey]);

                    // Log not found
                    $langrouter->logRequest('Culture key not found in URI');

                    // Redirect to valid context
                    if ($switched) {
                        if (!empty($modx->context)) {
                            $siteUrl = $modx->context->getOption('site_url');
                            $modx->sendRedirect($siteUrl);
                        } else {
                            $langrouter->logMessage('The switched MODX context was not valid');
                        }
                    } else {
                        $langrouter->logMessage('Context switch to "' . $contextmap[$cultureKey] . '" was not valid.');
                    }
                }

                // Serve site_start when no resource is requested
                if (empty($_REQUEST[$queryKey])) {
                    $langrouter->logMessage('Query is empty.');
                    $siteStart = (!empty($modx->context)) ? ($modx->context->getOption('site_start')) : $modx->getOption('site_start');
                    $langrouter->logDump($siteStart, 'Send forward to site_start');
                    $modx->sendForward($siteStart);
                }
            }
        }
        break;

    case 'OnContextSave':
    case 'OnContextRemove':
    case 'OnSiteRefresh':

        // Init LangRouter service class
        $corePath = $modx->getOption('langrouter.core_path', null, $modx->getOption('core_path') . 'components/langrouter/');
        $langrouter = $modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', array(
            'core_path' => $corePath
        ));

        // Cache contexts and their cultureKeys
        $babelContexts = explode(',', $modx->getOption('babel.contextKeys'));
        $contextmap = $langrouter->contextmap($babelContexts);
        $modx->cacheManager->set($langrouter->getOption('cacheKey'), $contextmap, 0, $langrouter->getOption('cacheOptions'));
        break;

}
return;
