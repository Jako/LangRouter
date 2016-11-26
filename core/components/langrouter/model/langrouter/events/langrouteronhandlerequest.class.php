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
            $cultureKey = (strpos($query, '/') !== false) ? substr($query, 0, strpos($query, '/')) : $query;

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
                    // Detect context key
                    $contextKey = $this->langrouter->contextKeyDetect($contextmap);

                    // Switch the context
                    $switched = $this->modx->switchContext($contextKey);

                    // Log not found
                    $this->langrouter->logRequest('Culture key not found in URI');

                    if ($switched) {
                        if (!empty($this->modx->context)) {
                            $get = $_GET;
                            unset($get[$queryKey]);
                            $query = (empty($get)) ? $query : $query . '?' . http_build_query($get);
                            $siteUrl = $this->modx->context->getOption('site_url') . $query;

                            // Redirect to valid context
                            $this->langrouter->logMessage('Redirect to ' . $siteUrl);
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
