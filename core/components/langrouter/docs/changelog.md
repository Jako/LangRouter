# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.0] - 2021-12-30

### Changed

- Code refactoring
- Full MODX 3 compatibility

## [1.3.4] - 2020-03-05

### Changed

- Check if langrouter.contextDefault or babel.contextDefault contains a valid context key

## [1.3.3] - 2020-01-14

### Changed

- Bugfix for a PHP warning

## [1.3.2] - 2020-01-07

### Changed

- Bugfix for switching to the right language on browsers not sending a q factor

## [1.3.1] - 2019-08-20

### Changed

- PHP/MODX version check
- Normalized/refactored code

## [1.3.0] - 2017-11-13

### Added

- langrouter.contextKeys and langrouter.contextDefault system setting
- handling of cultureKeyAliases context setting

### Changed

- Bugfix for handling a locale context setting
- Improved context key detection

## [1.2.0] - 2017-01-14

### Added

- Allow the default language to be served at /

### Changed

- Bugfix for not detected language context
- Improved detecton of weighted browser languages
- Fixing an issue with ClientConfig

## [1.1.3] - 2016-12-02

### Added

- Compatibility with ajaxMode of pdoPage

## [1.1.2] - 2016-11-25

### Added

- Preserve the original query parameters if the culture key is not set
- Preserve the original url path if the culture key is not set

### Changed

- Bugfix for urls with no path delimiter and no culture key set

## [1.1.1] - 2016-10-23

### Changed

- Improve culture key and browser language match

## [1.1.0] - 2016-10-16

### Added

- Response code for the redirect to the right context, if the culture key is not set (changeable by a MODX system setting)

## [1.0.8] - 2016-10-06

### Changed

- Bugfix for an error, when no client culture key was found

## [1.0.7] - 2016-10-05

### Changed

- Bugfix for compatibility with debugParser and other plugins

## [1.0.6] - 2016-04-07

### Changed

- Caching issue with the context map

## [1.0.5] - 2016-03-10

### Added

- Cached context map

### Changed

- Improved debug mode

## [1.0.4] - 2016-03-03

- Bugfix for web context

### Added

- The plugin sets a context specific locale (if defined)

## [1.0.3] - 2015-09-03

### Changed

- Bugfix for debug mode

## [1.0.2] - 2015-06-27

### Changed

- Bugfix for creating babel.contextDefault

## [1.0.1] - 2015-03-30

### Added

- Set babel.contextDefault system setting during package installation

### Changed

- Bugfixes

## [1.0.0] - 2015-02-05

### Added

- Initial release for MODX Revolution
