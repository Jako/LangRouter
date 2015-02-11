#LangRouter

Installable extended Version of https://gist.github.com/gadamiak/3812853

This plugin is meant to be used with Babel for MODX Revolution. It takes care of switching contexts, which hold translations, depending on URL requested by client. LangRouter works with so called subfolder based setup, in which many languages are served under a single domain but are differentiated by a virtual subfolder indicating the language, eg. *mydomain.com/pl/*.

The routing work as follows:

- If an URI contains cultureKey, which is defined in Babel configuration, then the matching context is served.
- If an URI doesn't contain cultureKey (or one not defined in Babel configuration) AND at least one of the client's browser accepted languages is defined in Babel configuration, then the matching context is served.
- Otherwise the default context is served.

LangRouter works out-of-the-box and doesn't require any changes to URL rewrite rules in the webserver configuration. All routing is handled internally by MODX. This greatly simplifies the setup and provides portability. LangRouter was tested with Apache and Lighttpd.

**CAUTION:** Please don't activate the `friendly_urls_strict` system setting, if you are using LangRouter. That could cause nasty redirect loops.

##Setup

1. Prepare your contexts as you normally would for Babel.
2. For each context set `base_url` to `/`.
3. For each context set `site_url` to `{server_protocol}://{http_host}{base_url}{cultureKey}/`
4. Add new system setting `babel.contextDefault` and set it to the default context, which should be served when no language is specified in request, eg. `pl`.
5. Include static files from the assets folder with `[[++assets_url]]path/to/static_file`. In head element use `<base href="[[++site_url]]" />`.
6. Use default URL generation scheme in MODX (ie. relative).
