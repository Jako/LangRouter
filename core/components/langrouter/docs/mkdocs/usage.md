## Prepare your contexts

After (or before) the installation of LangRouter you have to prepare your
contexts.

1. Create one context for each language and name it with the language name. Normally the context key would be equal with the cultureKey context setting of that language, i.e. `en` as **context key** and `English` as **context name** when the cultureKey is `en`.
2. Create the context setting **base_url** in each context and set it to `/`.
2. Create the context setting **cultureKey** in each context and set it to the according cultureKey, i.e. `en`.
3. For each context create a **site_url** context setting and fill it with the following value: `{url_scheme}{http_host}{base_url}{cultureKey}/`. MODX handles the placeholder replacements in that setting on its own.
4. Fill the MODX system setting **babel.contextDefault** with the context key of the default language, if you did not fill this during the installation of LangRouter.
5. In head section of the template insert the following line `<base href="[[!++site_url]]" />`.
6. Include the static files from the assets folder in your installation with `[[++assets_url]]path/to/static_file`, i.e. `<link href="[[++assets_url]]css/site.css" rel="stylesheet"/>` or `<img src="[[++assets_url]]images/whatever.jpg" … />`. You could use `[[++base_url]]path/to/static_file`, if your assets are not located xinside of the assets folder.
7. Set the MODX system setting **link_tag_scheme** to `-1` (URL is relative to site_url)

To create these settings easily, you could use the [Cross Contexts
Settings](https://modx.com/extras/package/crosscontextssettings) extra available
on MODX Extras.

### Example

Example settings for an `en` context

Context setting | Value
----------------|------
base_url | `/`
cultureKey | `en`
site_url | `{url_scheme}{http_host}{base_url}{cultureKey}/`

## System settings

The following MODX system settings are available in the namespace `langrouter`:

Key | Description | Default
----|-------------|--------
langrouter.debug | Log debug informations in the MODX ystem log. | No
langrouter.response_code | Response code for the redirect to the right context, if the culture key is not set. | `HTTP/1.1 301 Moved Permanently`

!!! caution 
    Please don't activate the **friendly_urls_strict** MODX system setting, if you use LangRouter. That could cause nasty redirect loops.
