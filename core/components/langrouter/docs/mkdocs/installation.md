## Install from MODX Extras

Search for LangRouter in the Package Manager of your MODX installation and
install it in there. You have to fill the context key of the default language
during the setup. See in [Usage](/usage/) for the possible value.

## Manual installation

If you can't access the MODX Extras Repository in your MODX installation, you
can manually install LangRouter.

* Download the transport package from [MODX Extras](http://modx.com/extras/package/langrouter) (or one of the pre built transport packages in [_packages](https://github.com/Jako/LangRouter/tree/master/_packages))
* Upload the zip file to your MODX installation's `core/packages` folder or upload it manually in the MODX Package Manager.
* In the Package Manager of your MODX installation select 'Search locally for packages' from the dropdown button.
* LangRouter should now show up in the list of available packages. Click the corresponding 'Install' button and follow the instructions to complete the installation.

## Build it from source

To build and install the package from source you could use [Git Package
Management](https://github.com/TheBoxer/Git-Package-Management). The GitHub
repository of LangRouter contains a
[config.json](https://github.com/Jako/LangRouter/blob/master/_build/config.json)
to build that package locally. Use this option, if you want to debug LangRouter
and/or contribute bugfixes and enhancements.
