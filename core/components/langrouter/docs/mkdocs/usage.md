## Usage

After (or before) the installation of LangRouter you have to prepare your contexts.

1. Create one context for each language with the later language subfolder name as **context key** and name it with the language name. Normally the context key would be equal with the cultureKey of that language, i.e. `en` as **context key** and `English` as **context name**.
2. Create the context setting **base_url** in each context and set it to `/`.
3. For each context create a **site_url** context setting and fill it with the following value: `{url_scheme}{http_host}{base_url}{cultureKey}/`. MODX handles the placeholder replacements in that setting on its own.
4. Fill the MODX system setting **babel.contextDefault** with the context key of the default language, if you did not filled it during the Installation of LangRouter.
5. In head section of the template insert the following line `<base href="[[!++site_url]]" />`.
6. Include the static files from the assets folder in your installation with `[[++assets_url]]path/to/static_file`, i.e. `<link href="[[++assets_url]]css/site.css" rel="stylesheet"/>` or `<img src="[[++assets_url]]images/whatever.jpg" … />`
7. Set the MODX system setting **link_tag_scheme** to `-1` (URL is relative to site_url)

To create these settings easily, you could use the [Cross Contexts Settings](https://modx.com/extras/package/crosscontextssettings) extra available on MODX Extras.

To debug the LangRouter configuration, set the MODX system setting debug in the namespace `langrouter` to true. The debug information is logged in the MODX error log.

## Caution

Please don't activate the **friendly_urls_strict** MODX system setting, if you use LangRouter. That could cause nasty redirect loops.


<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.partout.info/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 15]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//piwik.partout.info/piwik.php?idsite=15" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
