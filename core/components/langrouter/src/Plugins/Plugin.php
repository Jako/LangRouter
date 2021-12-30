<?php
/**
 * Abstract plugin
 *
 * @package langrouter
 * @subpackage plugin
 */

namespace TreehillStudio\LangRouter\Plugins;

use modX;
use LangRouter;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var LangRouter $langrouter */
    protected $langrouter;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx = &$modx;
        $corePath = $this->modx->getOption('langrouter.core_path', null, $this->modx->getOption('core_path') . 'components/langrouter/');
        $this->langrouter = $this->modx->getService('langrouter', 'LangRouter', $corePath . 'model/langrouter/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init(): bool
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}