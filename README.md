# Google Tag Manager tracking integration for Spryker
[![Build Status](https://travis-ci.org/fond-of/spryker-google-tagmanager.svg?branch=master)](https://travis-ci.org/fond-of/spryker-google-tagmanager)
[![PHP from Travis config](https://img.shields.io/travis/php-v/symfony/symfony.svg)](https://php.net/)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/fond-of-spryker/google-tagmanager)

Google Analytics tracking integration for Spryker



## Installation

```
composer require fond-of-spryker/google-tagmanager
```

## 1. Add the Container ID in the configuration file 

```
// ---------- Google Tag Manager
$config[GoogleTagManagerConstants::CONTAINER_ID] = 'GTM-XXXX'; 
```

## 2. Add twig service provider to YvesBootstrap.php in registerServiceProviders()

```
$this->application->register(new GoogleTagManagerTwigServiceProvider());
```

## 3. Add the Twig Extension add the end of the head.twig

```
{{ fondOfSpykerGoogleTagManager('@GoogleTagManager/partials/tag.twig') }}
```

