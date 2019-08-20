# LangRouter

LangRouter is a language routing plugin for MODx Revolution that is meant to be
used with Babel. It takes care of switching contexts that hold the translations,
depending on the URL requested by the client.

### Requirements

- MODX Revolution 2.6+
- PHP v5.6+

### Features

- LangRouter works out-of-the-box and doesn't require any URL rewrite rule changes in the webserver configuration.
- It checks the client's browser accepted languages and switches to the first accepted and available language context.
- All routing is handled internally by MODX. This greatly simplifies the setup and provides portability. 
- LangRouter was tested with Apache and Lighttpd.
