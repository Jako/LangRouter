<?php

/**
 * @package langrouter
 * @subpackage plugin
 */
class LangRouterOnHandleRequest extends LangRouterPlugin
{
    public function run()
    {
        if ($this->modx->context->get('key') != "mgr" && MODX_API_MODE == false) {
            $this->langrouter->logRequest('Unhandled request');

            // Get contexts and their cultureKeys
            $contextmap = $this->modx->cacheManager->get($this->langrouter->getOption('cacheKey'), $this->langrouter->getOption('cacheOptions'));
            if (empty($contextmap)) {
                $babelContexts = explode(',', $this->modx->getOption('langrouter.contextKeys', null, $this->modx->getOption('babel.contextKeys'), true));
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
                    $contextKey = $this->modx->context->get('key');
                    if ($contextKey != $contextmap[$cultureKey]) {
                        $this->modx->switchContext($contextmap[$cultureKey]);
                    }

                    // Remove cultureKey from request
                    $_REQUEST[$queryKey] = preg_replace('~^' . preg_quote($cultureKey, '~') . '/(.*)~', '$1', $_REQUEST[$queryKey]);
                    $_SERVER['REQUEST_URI'] = preg_replace('~^/' . preg_quote($cultureKey, '~') . '/(.*)~', '/$1', $_SERVER['REQUEST_URI']);

                    $this->langrouter->logRequest('Culture key found in URI');
                    $this->modx->cultureKey = $cultureKey;
                } else {
                    $contextKey = $this->langrouter->contextKeyDetect($contextmap);

                    $switched = $this->modx->switchContext($contextKey);
                    $this->langrouter->logRequest('Culture key not found in URI');

                    if ($switched) {
                        if (!empty($this->modx->context)) {
                            $get = $_GET;
                            unset($get[$queryKey]);
                            $query = (empty($get)) ? $query : $query . '?' . http_build_query($get);
                            $siteUrl = $this->modx->context->getOption('site_url') . $query;

                            $currentUrl = (isset($_SERVER['HTTPS']) === true ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                            if ($siteUrl != $currentUrl) {
                                // Redirect to valid context
                                $this->langrouter->logMessage('Redirect to ' . $siteUrl);
                                $this->modx->sendRedirect($siteUrl, array('responseCode' => $this->langrouter->getOption('response_code')));
                            }
                        } else {
                            $this->langrouter->logMessage('The switched MODX context was not valid');
                        }
                    } else {
                        $this->langrouter->logMessage('Context switch to "' . $contextKey . '" was not valid.');
                    }
                }

                // Set locale since $this->modx->_initCulture is called before OnHandleRequest
                if ($this->modx->getOption('setlocale', null, true)) {
                    $locale = setlocale(LC_ALL, null);
                    setlocale(LC_ALL, $this->modx->context->getOption('locale', $locale));
                }
            }

            if (!$cultureKey && $query != '') {
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

              $switched = $this->modx->switchContext($contextKey);

              $errorPage = (!empty($this->modx->context)) ? ($this->modx->context->getOption('error_page')) : $this->modx->getOption('error_page');

              // Log default
              $this->langrouter->logDump($contextKey, 'Default context key');
              $this->modx->sendForward($errorPage);
            }
        }
    }
}
