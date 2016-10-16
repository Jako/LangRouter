<?php
/**
 * @package langrouter
 * @subpackage plugin
 */

abstract class LangRouterPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var LangRouter $langrouter */
    protected $langrouter;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx = &$modx;
        $corePath = $this->modx->getOption('langrouter.core_path', null, $this->modx->getOption('core_path') . 'components/langrouter/');
        $this->langrouter = $this->modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', array(
            'core_path' => $corePath
        ));
    }

    abstract public function run();
}