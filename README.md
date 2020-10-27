# Google Tag Manager integration for Spryker
[![Build Status](https://travis-ci.org/fond-of/spryker-google-tagmanager.svg?branch=master)](https://travis-ci.org/fond-of/spryker-google-tagmanager)
[![PHP from Travis config](https://img.shields.io/travis/php-v/symfony/symfony.svg)](https://php.net/)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/fond-of-spryker/google-tagmanager)

###breaking changes in version 5.0
- Since version 5.0 google tag manager is using plugins for most parts.
- Enhanced ecommerce is not longer implement in google tag manager. i already working on a own package for that.
- Some plugins are really special for our usecase, implement your owns.

## Installation

```
composer require fond-of-spryker/google-tagmanager
```

#### 1. Add the Container ID in the configuration file 

```
// ---------- Google Tag Manager
$config[GoogleTagManagerConstants::CONTAINER_ID] = 'GTM-XXXX'; 
```

#### 2. Enable the Module in the configuration file 
```
// ---------- Google Tag Manager
$config[GoogleTagManagerConstants::ENABLED] = true;
```

#### 3. Include the namespace as a core namespace in the configuration file 
```
$config[KernelConstants::CORE_NAMESPACES] = [
    [...]
    'FondOfSpryker'
];
```

#### 4. Add twig service provider to YvesBootstrap.php in registerServiceProviders()

```
$this->application->register(new GoogleTagManagerTwigServiceProvider());
```

#### 5. Add the Twig Extension in the neccessary Twig Templates

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

## general usage
### example DefaultVariableBuilder

DefaultVariableBuilder provides attributes inside datalayer for every page. By default there no plugins registered in 
the module dependency provider, use your own i.e.:

```
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerDependencyProvider as FondOfGoogleTagManagerDependencyProvider;

class GoogleTagManagerDependencyProvider extends FondOfGoogleTagManagerDependencyProvider
{
    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface[]
     */
    protected function getDefaultVariableBuilderFieldPlugins(): array
    {
        return [
            new DefaultFieldCustomerEmailHashPlugin(),
            new DefaultFieldStoreNamePlugin(),
            new DefaultFieldCurrencyPlugin(),
            new DefaultFieldInternalPlugin(),
        ];
    }
```

existing plugins, located at FondOfSpryker/Yves/GoogleTagManager/Plugin/VariableBuilder/[DefaultVariableBuilder]

if you need custom plugins just implement DefaultFieldPluginInterface in your class and put it into your 
DependencyProvider. Interface is located at FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder. dont forget 
extend the transfer object with the new property.

if you need your own logic which cant be solved as plugin, you can use your own DefaultVariableBuilder too.
- implement DefaultVariableBuilderPluginInterface in your class
- overwrite addProductVariableBuilderPlugin() in your dependency provider

```
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerDependencyProvider as FondOfGoogleTagManagerDependencyProvider;

class GoogleTagManagerDependencyProvider extends FondOfGoogleTagManagerDependencyProvider
{
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addDefaultVariableBuilderPlugin(Container $container): Container
    {
        $container->set(static::DEFAULT_VARIABLE_BUILDER_PLUGIN, function () {
            return new CustomDefaultVariableBuilderPlugin();
        });

        return $container;
    }
}
```
