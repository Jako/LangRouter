<?php

/**
 * @package langrouter
 * @subpackage plugin
 */
class LangRouterOnHandleRequest extends LangRouterPlugin
{
    public function run()
    {
        if ($this->modx->context->get('key') != "mgr") {
            // Log start
            $this->langrouter->logRequest('Unhandled request');

            // Get contexts and their cultureKeys
            $contextmap = $this->modx->cacheManager->get($this->langrouter->getOption('cacheKey'), $this->langrouter->getOption('cacheOptions'));
            if (empty($contextmap)) {
                $babelContexts = explode(',', $this->modx->getOption('babel.contextKeys'));
                $contextmap = $this->langrouter->contextmap($babelContexts);
                $this->modx->cacheManager->set($this->langrouter->getOption('cacheKey'), $contextmap, 0, $this->langrouter->getOption('cacheOptions'));
            }
            $this->langrouter->logDump($contextmap, 'contextmap');

            // Determine language from request
            $queryKey = $this->modx->getOption('request_param_alias', null, 'q');
            $query = (isset($_REQUEST[$queryKey])) ? $_REQUEST[$queryKey] : '';
            $cultureKey = substr($query, 0, strpos($query, '/'));

            if ($cultureKey || $query === '') {
                // Serve the proper context and language
                if (array_key_exists(strtolower($cultureKey), array_change_key_case($contextmap))) {
                    // Culture key is found, so switch the context
                    $this->modx->switchContext($contextmap[$cultureKey]);

                    // Remove cultureKey from request
                    $_REQUEST[$queryKey] = substr($query, strlen($cultureKey) + 1);

                    // Log found
                    $this->langrouter->logRequest('Culture key found in URI');

                    // Set culture key
                    $this->modx->cultureKey = $cultureKey;

                    // Set locale since $this->modx->_initCulture is called before OnHandleRequest
                    if ($this->modx->getOption('setlocale', null, true)) {
                        $locale = setlocale(LC_ALL, null);
                        setlocale(LC_ALL, $this->modx->getOption('locale', null, $locale, true));
                    }
                } else {
                    // Culture key is has to be detected
                    $clientLangs = array_flip($this->langrouter->clientLangDetect());

                    $clientCultureKey = '';
                    foreach($contextmap as $k => $v) {
                        $context = explode('-', $v);
                        $matches = preg_grep('/' . $context[0] . '/', $clientLangs);
                        if (count($matches) > 0) {
                            // Use first entry of detected client culture key
                            $clientCultureKey = $k;
                            break;
                        }
                    }

                    if ($clientCultureKey) {
                        $cultureKey = $clientCultureKey;
                        $contextKey = $contextmap[$cultureKey];
                        // Log detected
                        $this->langrouter->logDump($cultureKey, 'Detected culture key');
                        $this->langrouter->logDump($contextKey, 'Detected context key');
                    } else {
                        // Use default context key
                        $contextKey = trim($this->modx->getOption('babel.contextDefault', null, 'web'));
                        // Log default
                        $this->langrouter->logDump($contextKey, 'Default context key');
                    }

                    // Switch the context
                    $switched = $this->modx->switchContext($contextKey);

                    // Log not found
                    $this->langrouter->logRequest('Culture key not found in URI');

                    // Redirect to valid context
                    if ($switched) {
                        if (!empty($this->modx->context)) {
                            $siteUrl = $this->modx->context->getOption('site_url');
                            $this->modx->sendRedirect($siteUrl, array('responseCode' => $this->langrouter->getOption('response_code')));
                        } else {
                            $this->langrouter->logMessage('The switched MODX context was not valid');
                        }
                    } else {
                        $this->langrouter->logMessage('Context switch to "' . $contextKey . '" was not valid.');
                    }
                }

                // Serve site_start when no resource is requested
                if (empty($_REQUEST[$queryKey])) {
                    $this->langrouter->logMessage('Query is empty.');
                    $siteStart = (!empty($this->modx->context)) ? ($this->modx->context->getOption('site_start')) : $this->modx->getOption('site_start');
                    $this->langrouter->logDump($siteStart, 'Send forward to site_start');
                    $this->modx->sendForward($siteStart);
                }
            }
        }
    }
}
