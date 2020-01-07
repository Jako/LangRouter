<?php
/**
 * LangRouter classfile
 *
 * Copyright 2012-2014 by Grzegorz Adamiak <https://github.com/gadamiak>
 * Copyright 2015-2020 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package langrouter
 * @subpackage classfile
 */

/**
 * Class LangRouter
 */
class LangRouter
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'langrouter';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.3.2';

    /**
     * The class options
     * @var array $options
     */
    public $options = array();

    /**
     * LangRouter constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = array())
    {
        $this->modx = &$modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path') . "components/{$this->namespace}/");
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path') . "components/{$this->namespace}/");
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url') . "components/{$this->namespace}/");

        // Load some default paths for easier management
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        // Add default options
        $this->options = array_merge($this->options, array(
            'debug' => (bool)$this->getOption('debug', $options, false),
            'response_code' => $this->getOption('response_code', $options, 'HTTP/1.1 301 Moved Permanently'),
            'cacheKey' => 'contextmap',
            'cacheOptions' => array(
                xPDO::OPT_CACHE_KEY => $this->namespace,
                xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache')),
            )
        ));

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     * Get contexts and their cultureKeys
     *
     * @param array $contexts
     * @return array
     */
    public function contextmap($contexts)
    {
        $contextmap = array();
        foreach ($contexts as $context) {
            $ctx = $this->modx->getContext($context);
            if (isset($ctx->config['cultureKey'])) {
                $contextmap[$ctx->config['cultureKey']] = trim($context);
            }
            if (isset($ctx->config['cultureKeyAliases'])) {
                $cultureKeyAliases = explode(',', $ctx->config['cultureKeyAliases']);
                foreach ($cultureKeyAliases as $cultureKeyAlias) {
                    $contextmap[$cultureKeyAlias] = trim($context);
                }
            }
        }
        return $contextmap;
    }

    /**
     * Debugs request handling
     *
     * @param string $message
     */
    public function logRequest($message = 'Request')
    {
        if ($this->getOption('debug')) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message . ':' .
                "\nREQUEST_URI:   " . htmlspecialchars($_SERVER['REQUEST_URI']) .
                "\nREDIRECT_URI:  " . htmlspecialchars($_SERVER['REDIRECT_URI']) .
                "\nQUERY_STRING:  " . htmlspecialchars($_SERVER['QUERY_STRING']) .
                "\nq:             " . htmlspecialchars($_REQUEST['q']) .
                "\nContext:       " . (($this->modx->context) ? $this->modx->context->get('key') : '- none -') .
                "\nSite start:    " . (($this->modx->context) ? $this->modx->context->getOption('site_start') : $this->modx->getOption('site_start')),
                '', 'LangRouter');
        }
    }

    /**
     * Dump variables
     *
     * @param mixed $var
     * @param string $name
     */
    public function logDump($var, $name = '')
    {
        if ($this->getOption('debug')) {
            $name = ($name) ? $name . ': ' : '';
            ob_start();
            var_dump($var);
            $dump = ob_get_clean();
            $this->modx->log(modX::LOG_LEVEL_ERROR, $name . $dump, '', 'LangRouter');
        }
    }

    /**
     * Log message
     *
     * @param string $message
     */
    public function logMessage($message = '')
    {
        if ($this->getOption('debug')) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message, '', 'LangRouter');
        }
    }

    /**
     * Detects client language preferences and returns associative array sorted
     * by importance (q factor)
     *
     * @return array
     */
    private function clientLangDetect()
    {
        $langs = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

            if (count($lang_parse[1])) {
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 (or decremented by 0.01) for any language without q factor
                $q = 1;
                foreach ($langs as $lang => $val) {
                    if ($val === '') {
                        $langs[$lang] = $q;
                        $q -= 0.01;
                    } else {
                        $q = $val - 0.01;
                    }
                }
                arsort($langs, SORT_NUMERIC);
            }
        }
        return $langs;
    }

    /**
     * Detect a context key
     *
     * @param array $contextmap
     * @return string
     */
    public function contextKeyDetect($contextmap)
    {
        $clientLangs = array_flip($this->clientLangDetect());

        $clientCultureKeys = array();
        foreach ($contextmap as $k => $v) {
            $context = preg_split('/[-_]/', $k);
            $matches = preg_grep('/^' . $context[0] . '/', $clientLangs);
            if (count($matches) > 0) {
                // Get the q factor of the current clientLang
                $clientCultureKeys[$k] = floatval(key($matches));
            }
        }
        arsort($clientCultureKeys, SORT_NUMERIC);

        if (count($clientCultureKeys)) {
            $cultureKey = key($clientCultureKeys);
            $contextKey = $contextmap[$cultureKey];
            $this->logDump($cultureKey, 'Detected culture key');
            $this->logDump($contextKey, 'Detected context key');
        } else {
            // Use default context key
            $contextKey = trim($this->modx->getOption('babel.contextDefault', null, 'web'));
            $this->logDump($contextKey, 'Default context key');
        }
        return $contextKey;
    }
}
