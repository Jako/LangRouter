# Intro

LangRouter is a language routing plugin for MODx Revolution that is meant to be used with Babel. It takes care of switching contexts that hold the translations, depending on the URL requested by the client.

### Requirements

* MODX Revolution 2.2.4+
* PHP v5.3+

### Features

* LangRouter works out-of-the-box and doesn't require any URL rewrite rule changes in the webserver configuration. 
* It checks the client's browser accepted languages and switches to the first accepted and available language context.
* All routing is handled internally by MODX. This greatly simplifies the setup and provides portability. 
* LangRouter was tested with Apache and Lighttpd.

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
