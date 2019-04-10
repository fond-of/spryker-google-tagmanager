# Google Tag Manager integration for Spryker
[![Build Status](https://travis-ci.org/fond-of/spryker-google-tagmanager.svg?branch=master)](https://travis-ci.org/fond-of/spryker-google-tagmanager)
[![PHP from Travis config](https://img.shields.io/travis/php-v/symfony/symfony.svg)](https://php.net/)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/fond-of-spryker/google-tagmanager)

Google Tag Manager integration for Spryker


## Installation

```
composer require fond-of-spryker/google-tagmanager
```

## 1. Add the Container ID in the configuration file 

```
// ---------- Google Tag Manager
$config[GoogleTagManagerConstants::CONTAINER_ID] = 'GTM-XXXX'; 
```

## 2. Enable the Module in the configuration file 
```
// ---------- Google Tag Manager
$config[GoogleTagManagerConstants::ENABLED] = true;
```

## 3. Add twig service provider to YvesBootstrap.php in registerServiceProviders()

```
$this->application->register(new GoogleTagManagerTwigServiceProvider());
```

## 4. Add the Twig Extension in the neccessary Twig Templates

```
  Application/layout/layout.twig 
  between <head></head> tags
  
  {% block googletagmanager_data_layer %} {{ dataLayer('other', {}) }}{% endblock %} 
  {{ googleTagManager('@GoogleTagManager/partials/tag.twig') }}
  
  after <body> tag
  {{ googleTagManager('@GoogleTagManager/partials/tag-noscript.twig') }}
```

```
  Catalog/catalog/index.twig 
  {% block googletagmanager_data_layer %}
      {% set params = { 'category' : category, 'products' : products} %}
      {{ dataLayer('category', params) }}
  {% endblock %}
```

```
  Product/product/detail.twig 
  {% block googletagmanager_data_layer %}
      {% set params = { 'product' : product} %}
      {{ dataLayer('product', params) }}
  {% endblock %}
```

```
  Cart/cart/index.twig 
  {% block googletagmanager_data_layer %}
      {{ dataLayer('cart', {}) }}
  {% endblock %}
```

```
  Checkout/checkout/partial/success.twig 
  {% block googletagmanager_data_layer %}
      {% set params = { 'order' : orderTransfer} %}
      {{ dataLayer('order', params) }}
  {% endblock %}
```

