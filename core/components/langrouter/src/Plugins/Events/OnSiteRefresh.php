<?php
/**
 * @package langrouter
 * @subpackage plugin
 */

namespace TreehillStudio\LangRouter\Plugins\Events;

use TreehillStudio\LangRouter\Plugins\Plugin;

class OnSiteRefresh extends Plugin
{
    public function process()
    {
        // Cache contexts and their cultureKeys
        $babelContexts = explode(',', $this->modx->getOption('langrouter.contextKeys', null, $this->modx->getOption('babel.contextKeys'), true));
        $contextmap = $this->langrouter->contextmap($babelContexts);
        $this->modx->cacheManager->set($this->langrouter->getOption('cacheKey'), $contextmap, 0, $this->langrouter->getOption('cacheOptions'));
    }
}
