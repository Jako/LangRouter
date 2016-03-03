<?php
/* LangRouter
 * ==========
 *
 * This plugin is meant to be used with Babel extra for MODX Revolution. It
 * takes care of switching contexts, which hold translations, depending on URL
 * requested by client. LangRouter works with so called subfolder based setup,
 * in which many languages are served under a single domain but are
 * differentiated by a virtual subfolder indicating the language, eg.
 * mydomain.com/pl/.
 *
 * The routing work as follows:
 * - if URI contains cultureKey, which is defined in Babel configuration, then
 *   the matching context is served
 * - if URI doesn't contain cultureKey (or one not defined in Babel
 *   configuration) AND at least one of the client's accepted languages is
 *   defined in Babel configuration, then the matching context is served
 * - otherwise the default context is served
 *
 * LangRouter works out-of-the-box and doesn't require any changes to URL
 * rewrite rules in the webserver configuration. All routing is handled
 * internally by MODX. This greatly simplifies the setup and provides
 * portability. LangRouter was tested with Apache and Lighttpd.
 *
 * Setup:
 * 1. Prepare your contexts as you normally would for Babel.
 * 2. For each context set `base_url` to `/`.
 * 3. For each context set `site_url` to
 *    `{server_protocol}://{http_host}{base_url}{cultureKey}/`
 * 4. Add new system setting `babel.contextDefault` and set it to the default
 *    context, which should be served when no language is specified in
 *    request, eg. `pl`.
 * 5. Include static files from the assets folder with
 *    `[[++assets_url]]path/to/static_file`.
 * 6. In template header use `<base href="[[++site_url]]" />`.
 * 7. Use default URL generation scheme in MODX (ie. relative).
 *
 * This code is shared AS IS. Use at your own risk.
 */

if ($modx->context->get('key') != "mgr") {

    $debug = $modx->getOption('langrouter.debug', null, false, true);

    /*
     * Debugs request handling
     */
    function logRequest($message = 'Request')
    {
        global $modx;
        $modx->log(modX::LOG_LEVEL_ERROR, $message . ':'
            . "\n REQUEST_URI:   " . $_SERVER['REQUEST_URI']
            . "\n REDIRECT_URI:  " . $_SERVER['REDIRECT_URI']
            . "\n QUERY_STRING:  " . $_SERVER['QUERY_STRING']
            . "\n q:             " . $_REQUEST['q']
            . "\n Context:       " . (($modx->context) ? $modx->context->get('key') : '- none -')
            . "\n Site start:    " . (($modx->context) ? $modx->context->getOption('site_start') : $modx->getOption('site_start'))
        );
    }

    /*
     * Dumps variables to MODX log
     */
    function dump($var)
    {
        ob_start();
        var_dump($var);
        return ob_get_clean();
    }

    /*
     * Detects client language preferences and returns associative array sorted
     * by importance (q factor)
     */
    function clientLangDetect()
    {
        $langs = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            # break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

            if (count($lang_parse[1])) {
                # create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                # set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }

                # sort list based on value
                arsort($langs, SORT_NUMERIC);
            }
        }
        return $langs;
    }

    if ($debug) {
        logRequest('Unhandled request');
    }

    # Get contexts and their cultureKeys
    $babelContexts = explode(',', $modx->getOption('babel.contextKeys'));
    $languages = array();
    foreach ($babelContexts as $context) {
        $ctx = $modx->getContext($context);
        if (isset($ctx->config['cultureKey'])) {
            $languages[$ctx->config['cultureKey']] = trim($context);
        }
    }
    if ($debug) {
        $modx->log(modX::LOG_LEVEL_ERROR, dump($languages));
    }

    # Determine language from request
    $query = (isset($_REQUEST['q'])) ? $_REQUEST['q'] : '';
    $reqCultureKeyIdx = strpos($query, '/');
    $reqCultureKey = substr($query, 0, $reqCultureKeyIdx);

    if ($reqCultureKey) {
        # Serve the proper context and language
        if (array_key_exists(strtolower($reqCultureKey), array_change_key_case($languages))) {
            $modx->switchContext($languages[$reqCultureKey]);
            # Remove cultureKey from request
            $query = substr($query, $reqCultureKeyIdx + 1);
            if ($debug) {
                logRequest('Culture key found in URI');
            }
            $modx->cultureKey = $reqCultureKey;

            // set locale since $modx->_initCulture is called before OnHandleRequest
            if ($modx->getOption('setlocale', null, true)) {
                $locale = setlocale(LC_ALL, null);
                setlocale(LC_ALL, $modx->getOption('locale', null, $locale, true));
            }
        } else {
            $clientCultureKey = array_flip(array_intersect_key(clientLangDetect(), $languages));
            if ($clientCultureKey) {
                $contextDefault = current($clientCultureKey);
            } else {
                $contextDefault = trim($modx->getOption('babel.contextDefault', null, 'web'));
            }
            if ($debug) {
                $modx->log(modX::LOG_LEVEL_ERROR, dump($contextDefault));
            }
            $switched = $modx->switchContext($contextDefault);
            if ($debug) {
                logRequest('Culture key not found in URI');
            }
            if ($switched && $modx->context) {
                $siteUrl = $modx->context->getOption('site_url');
                $modx->sendRedirect($siteUrl);
            }
        }

        # Serve site_start when no resource is requested
        if (empty($query)) {
            if ($debug) {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Query is empty');
            }
            $siteStart = ($modx->context) ? ($modx->context->getOption('site_start')) : $modx->getOption('site_start');
            $modx->sendForward($siteStart);
        }
    }
}