LangRouter
==========

Installable extended Version of https://gist.github.com/gadamiak/3812853

This plugin is meant to be used with Babel for MODX Revolution. It takes care
of switching contexts, which hold translations, depending on URL requested by
client. LangRouter works with so called subfolder based setup, in which many
languages are served under a single domain but are differentiated by a virtual
subfolder indicating the language, eg. mydomain.com/pl/.

The routing work as follows:

- If an URI contains cultureKey, which is defined in Babel configuration, then
  the matching context is served.
- If an URI doesn't contain cultureKey (or one not defined in Babel
  configuration) AND at least one of the client's accepted languages is defined
  in Babel configuration, then the matching context is served.
- Otherwise the default context is served.

LangRouter works out-of-the-box and doesn't require any changes to URL rewrite
rules in the webserver configuration. All routing is handled internally by MODX.
This greatly simplifies the setup and provides portability. LangRouter was
tested with Apache and Lighttpd.

Usage
=====

After (or before) the installation of LangRouter you have to prepare your
contexts.

1. Create one context for each language with the later language subfolder name
   as 'context key' and name it with the language name. Normally the context
   key would be equal with the cultureKey of that language, i.e. `en` as
   'context key' and `English` as 'context name'.
2. Create the context setting 'base_url' in each context and set it to `/`.
3. For each context create a 'site_url' context setting and fill it with the
   following value: `{server_protocol}://{http_host}{base_url}{cultureKey}/`.
   MODX handles the placeholder replacements in that setting on its own.
4. Fill the MODX system setting 'babel.contextDefault' with the context key of
   the default language, if you did not filled it during the Installation of
   LangRouter.
5. In head section of the template insert the following line
   `<base href="[[++site_url]]" />`.
6. Include the static files from the assets folder in your installation with
   `[[++assets_url]]path/to/static_file`, i.e.
   `<link href="[[++assets_url]]css/site.css" rel="stylesheet"/>` or
   `<img src="[[++assets_url]]images/whatever.jpg" … />`
7. Set the MODX system setting 'link_tag_scheme' to `-1`
   (URL is relative to site_url)

CAUTION
=======

Please don't activate the 'friendly_urls_strict' MODX system setting, if you use LangRouter. That could cause nasty redirect loops.
