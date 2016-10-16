<?php

/**
 * @package langrouter
 * @subpackage plugin
 */
class LangRouterOnSiteRefresh extends LangRouterPlugin
{
    public function run()
    {
        // Cache contexts and their cultureKeys
        $babelContexts = explode(',', $this->modx->getOption('babel.contextKeys'));
        $contextmap = $this->langrouter->contextmap($babelContexts);
        $this->modx->cacheManager->set($this->langrouter->getOption('cacheKey'), $contextmap, 0, $this->langrouter->getOption('cacheOptions'));
    }
}