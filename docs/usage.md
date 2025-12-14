## Prepare your contexts

After (or before) the installation of LangRouter you have to prepare your
contexts.

1. Create one context for each language and name it with the language name.
   Normally the context key would be equal with the cultureKey context setting
   of that language, i.e. `en` as **context key** and `English` as **context
   name** when the cultureKey is `en`.
2. Create the context setting **base_url** in each context and set it to `/`.
3. Create the context setting **cultureKey** in each context and set it to the
   according cultureKey, i.e. `en`.
4. Optionally create the context setting **cultureKeyAliases** in each context
   and set it to a comma-separated list of other culture keys to which this
   context should respond, i.e. `de,nl`.
5. For each context create a **site_url** context setting and fill it with the
   following value: `{url_scheme}{http_host}{base_url}{cultureKey}/`. MODX
   handles the placeholder replacements in that setting on its own.
6. Fill the MODX system setting **babel.contextDefault** with the context key of
   the default language, if you did not fill this during the installation of
   LangRouter.
7. In head section of the template insert the following line `<base
   href="[[!++site_url]]">`.
8. Include the static files from the assets folder in your installation with
   `[[++assets_url]]path/to/static_file`, i.e. `<link
   href="[[++assets_url]]css/site.css" rel="stylesheet">` or `<img
   src="[[++assets_url]]images/whatever.jpg" … >`. You can use
   `[[++base_url]]path/to/static_file`, if your assets are not located inside the
   assets folder.
9. Set the MODX system setting **link_tag_scheme** to `-1` (URL is relative to
   site_url)

To create these settings easily, you can use the [Cross Contexts
Settings](https://modx.com/extras/package/crosscontextssettings) extra available
on MODX Extras.

### Example

Example settings for an `en` context

| Context setting   | Value                                            |
|-------------------|--------------------------------------------------|
| base_url          | `/`                                              |
| cultureKey        | `en`                                             |
| cultureKeyAliases | `de,nl`                                          |
| site_url          | `{url_scheme}{http_host}{base_url}{cultureKey}/` |

## System settings

LangRouter uses the following system settings in the namespace `langrouter`:

| Key                       | Name            | Description                                                                               | Default                        |
|---------------------------|-----------------|-------------------------------------------------------------------------------------------|--------------------------------|
| langrouter.contextDefault | Default Context | Default Context for LangRouter (supercedes babel.contextDefault system setting).          | -                              |
| langrouter.contextKeys    | Context Keys    | Comma-separated list of context keys which should be used to link multilingual resources. | -                              |
| langrouter.debug          | Debug           | Log debug information in MODX error log.                                                  | No                             |
| langrouter.response_code  | Response Code   | Response code for the redirect to the right context, if the culture key is not set.       | HTTP/1.1 301 Moved Permanently |

!!! caution "Redirect loops possible"

    Please don't activate the **friendly_urls_strict** MODX system setting, if
    you use LangRouter. That can cause nasty redirect loops.

## Usage with other extras

Some extras settings have to be changed to work well with LangRouter (and other
routing plugins):

### pThumb

Please set the system setting `phpthumbof.cache_url` to `/`. Otherwise, the
generated thumbnail path of the snippet/output filter will contain the
`{base_url}{cultureKey}` prefix.

On the other hand, you can add an additional .htaccess rule that removes this
prefix. In the following example, you need to change the list of culture keys.
It must be inserted before the friendly URLs part:

```
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(en|de|fr|nl)/assets(.*)$ assets$2 [L,QSA]
```
