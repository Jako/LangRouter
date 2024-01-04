# LangRouter

LangRouter is a language routing plugin for MODx Revolution that is meant to be
used with Babel. It takes care of switching contexts that hold the translations,
depending on the URL requested by the client.

### Requirements

* MODX Revolution 2.6+
* PHP 5.6+

### Features

* LangRouter works out-of-the-box and doesn't require any URL rewrite rule changes in the webserver configuration.
* It checks the client's browser accepted languages and switches to the first accepted and available language context.
* All routing is handled internally by MODX. This greatly simplifies the setup and provides portability. 
* LangRouter was tested with Apache and Lighttpd.

### License

The project is licensed under the [GPLv2 license](https://github.com/Jako/LangRouter/LICENSE.md).

### Translations [![Default Lexicon](https://hosted.weblate.org/widget/modx-extras/langrouter/standard/svg-badge.svg)](https://hosted.weblate.org/projects/modx-extras/langrouter/)

Translations of the package can be made for the [Default Lexicon](https://hosted.weblate.org/projects/modx-extras/langrouter/standard/)

