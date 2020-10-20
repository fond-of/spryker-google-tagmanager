<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart\AddProductControllerEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart\ChangeQuantityProductControllerEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart\RemoveProductControllerEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Checkout\PlaceOrderControllerEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Newsletter\NewsletterConfirmationEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Newsletter\NewsletterSubscribeEventHandler;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientBridge;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientBridge;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientBridge;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientBridge;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCartPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCheckoutBillingAddressPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCheckoutPaymentPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCheckoutSummaryPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceProductDetailPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceProductImpressionsPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhencedEcommercePurchasePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\BrandProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\CouponProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\Dimension10ProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\IdProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\NameProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\PriceProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\QuantityProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\VariantProductFieldMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapperPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\ProductSkuCategoryVariableBuilderPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariableBuilderPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\CustomerEmailHashVariableBuilderPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultCurrencyPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\InternalVariableBuilderPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\StoreNameVariableBuilderPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables\CustomerEmailHashNewsletterVariablesPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderDiscountPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductIdPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductNamePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductPriceExcludingTaxPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductPricePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductSalePricePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductSkuPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductTaxPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductTaxRatePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\BrandPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\EanPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\ImageUrlPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\NamePlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\QuantityPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\UrlPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandler;
use FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandler;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CART_CLIENT = 'CART_CLIENT';
    public const PRODUCT_CLIENT = 'PRODUCT_CLIENT';
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const TAX_PRODUCT_CONNECTOR_CLIENT = 'TAX_PRODUCT_CONNECTOR_CLIENT';
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';
    public const SESSION_CLIENT = 'SESSION_CLIENT';

    public const PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS = 'PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS';
    public const PRODUCT_VARIABLE_BUILDER_PLUGIN = 'PRODUCT_VARIABLE_BUILDER_PLUGIN';

    public const DEFAULT_VARIABLE_BUILDER_FIELD_PLUGINS = 'DEFAULT_VARIABLE_BUILDER_FIELD_PLUGINS';
    public const DEFAULT_VARIABLE_BUILDER_PLUGIN = 'DEFAULT_VARIABLE_BUILDER_PLUGIN';

    public const CATEGORY_VARIABLE_BUILDER_PLUGINS = 'CATEGORY_VARIABLE_BUILDER_PLUGINS';
    public const ORDER_VARIABLE_BUILDER_PLUGINS = 'ORDER_VARIABLE_BUILDER_PLUGINS';
    public const QUOTE_VARIABLE_BUILDER_PLUGINS = 'QUOTE_VARIABLE_BUILDER_PLUGINS';
    public const TRANSACTION_PRODUCT_VARIABLE_BUILDER_PLUGINS = 'TRANSACTION_PRODUCT_VARIABLE_BUILDER_PLUGINS';
    public const NEWSLETTER_VARIABLE_BUILDER_PLUGINS = 'NEWSLETTER_VARIABLE_BUILDER_PLUGINS';
    public const CART_CONTROLLER_EVENT_HANDLER = 'CART_CONTROLLER_EVENT_HANDLER';
    public const ENHANCED_ECOMMERCE_PAGE_PLUGINS = 'ENHANCED_ECOMMERCE_PAGE_PLUGINS';
    public const STORE = 'STORE';
    public const PRODUCT_FIELD_MAPPER_PLUGINS = 'PRODUCT_FIELD_MAPPER_PLUGINS';
    public const EEC_PRODUCT_MAPPER_PLUGIN = 'EEC_PRODUCT_MAPPER_PLUGIN';
    public const NEWSLETTER_SUBSCRIBE_EVENT_HANDLER = 'NEWSLETTER_SUBSCRIBE_EVENT_HANDLER';
    public const GTM_SESSION_HANDLER = 'GTM_SESSION_HANDLER';
    public const EEC_SESSION_HANDLER = 'EEC_SESSION_HANDLER';
    public const PRODUCT_IMAGE_STORAGE_CLIENT = 'PRODUCT_IMAGE_STORAGE_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $this->provideCartClient($container);
        $this->provideProductClient($container);
        $this->provideTaxProductConnectorClient($container);
        $this->addMoneyPlugin($container);
        $this->provideSessionClient($container);
        $this->addProductImageStorageClient($container);

        $this->addDefaultVariableBuilderPlugin($container);
        $this->addDefaultVariableBuilderFieldPlugins($container);

        $this->addProductVariableBuilderPlugin($container);
        $this->addProductVariableBuilderFieldPlugins($container);

        $this->addCategoryVariableBuilderPlugins($container);

        $this->addOrderVariableBuilderPlugins($container);
        $this->addQuoteVariableBuilderPlugins($container);
        $this->addTransactionProductVariableBuilderPlugins($container);
        $this->addEnhancedEcommercePlugins($container);
        $this->addProductStorageClient($container);
        $this->addStore($container);
        $this->addProductFieldMapperPlugins($container);

        $this->addGoogleTagManagerSessionHandler($container);
        $this->addEnhancedEcommerceSessionHandler($container);
        $this->addNewsletterVariableBuilderPlugins($container);
        $this->addNewsletterControllerEventHandler($container);
        $this->addEnhancedEcommerceProductMapperPlugin($container);
        $this->addCartControllerEventHandler($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductVariableBuilderPlugin(Container $container): Container
    {
        $container->set(static::PRODUCT_VARIABLE_BUILDER_PLUGIN, function () {
            return new ProductVariableBuilder();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addDefaultVariableBuilderPlugin(Container $container): Container
    {
        $container->set(static::DEFAULT_VARIABLE_BUILDER_PLUGIN, function () {
            return new DefaultVariableBuilderPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function provideCartClient(Container $container): Container
    {
        $container[static::CART_CLIENT] = function (Container $container) {
            return new GoogleTagManagerToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function provideProductClient(Container $container): Container
    {
        $container[static::PRODUCT_CLIENT] = function (Container $container) {
            return $container->getLocator()->product()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function provideTaxProductConnectorClient(Container $container): Container
    {
        $container[static::TAX_PRODUCT_CONNECTOR_CLIENT] = function (Container $container) {
            return $container->getLocator()->taxProductConnector()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, function () {
            return new MoneyPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideSessionClient(Container $container): Container
    {
        $container[static::SESSION_CLIENT] = function (Container $container) {
            return new GoogleTagManagerToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductVariableBuilderFieldPlugins(Container $container): Container
    {
        $container[static::PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS] = function () {
            return $this->getProductVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductVariableBuilderPluginInterface[]
     */
    protected function getProductVariableBuilderPlugins(): array
    {
        return [
            new ProductIdPlugin(),
            new ProductNamePlugin(),
            new ProductSkuPlugin(),
            new ProductPricePlugin(),
            new ProductPriceExcludingTaxPlugin(),
            new ProductSalePricePlugin(),
            new ProductTaxRatePlugin(),
            new ProductTaxPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryVariableBuilderPlugins(Container $container): Container
    {
        $container[static::CATEGORY_VARIABLE_BUILDER_PLUGINS] = function () {
            return $this->getCategoryVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    protected function getCategoryVariableBuilderPlugins(): array
    {
        return [
            new ProductSkuCategoryVariableBuilderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNewsletterVariableBuilderPlugins(Container $container): Container
    {
        $container[static::NEWSLETTER_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getNewsletterVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables\NewsletterVariablesPluginInterface[]
     */
    protected function getNewsletterVariableBuilderPlugins(Container $container): array
    {
        return [
            new CustomerEmailHashNewsletterVariablesPlugin($container[static::GTM_SESSION_HANDLER]),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addDefaultVariableBuilderFieldPlugins(Container $container): Container
    {
        $container[static::DEFAULT_VARIABLE_BUILDER_FIELD_PLUGINS] = function () {
            return $this->getDefaultVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    protected function getDefaultVariableBuilderPlugins(): array
    {
        return [
            new CustomerEmailHashVariableBuilderPlugin(),
            new StoreNameVariableBuilderPlugin(),
            new DefaultCurrencyPlugin(),
            new InternalVariableBuilderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addOrderVariableBuilderPlugins(Container $container): Container
    {
        $container[static::ORDER_VARIABLE_BUILDER_PLUGINS] = function () {
            return $this->getOrderVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderVariableBuilderPluginInterface[]
     */
    protected function getOrderVariableBuilderPlugins(): array
    {
        return [
            new OrderDiscountPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteVariableBuilderPlugins(Container $container): Container
    {
        $container[static::QUOTE_VARIABLE_BUILDER_PLUGINS] = function () {
            return $this->getQuoteVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[]
     */
    protected function getQuoteVariableBuilderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTransactionProductVariableBuilderPlugins(Container $container): Container
    {
        $container[static::TRANSACTION_PRODUCT_VARIABLE_BUILDER_PLUGINS] = function () {
            return $this->getTransactionProductVariableBuilderPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\TransactionProductVariableBuilderPluginInterface[]
     */
    public function getTransactionProductVariableBuilderPlugins(): array
    {
        return [
            new NamePlugin(),
            new EanPlugin(),
            new UrlPlugin($this->getConfig()),
            new BrandPlugin(),
            new ImageUrlPlugin(),
            new QuantityPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function addEnhancedEcommercePlugins(Container $container): Container
    {
        $container[static::ENHANCED_ECOMMERCE_PAGE_PLUGINS] = function () {
            return $this->getEnhancedEcommercePlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommercePageTypePluginInterface[]
     */
    protected function getEnhancedEcommercePlugins(): array
    {
        return [
            EnhancedEcommerceConstants::PAGE_TYPE_CART => new EnhancedEcommerceCartPlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_PRODUCT_DETAIL => new EnhancedEcommerceProductDetailPlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_CHECKOUT_BILLING_ADDRESS => new EnhancedEcommerceCheckoutBillingAddressPlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_CHECKOUT_PAYMENT => new EnhancedEcommerceCheckoutPaymentPlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_CHECKOUT_SUMMARY => new EnhancedEcommerceCheckoutSummaryPlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_PURCHASE => new EnhencedEcommercePurchasePlugin(),
            EnhancedEcommerceConstants::PAGE_TYPE_IMPRESSIONS => new EnhancedEcommerceProductImpressionsPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::PRODUCT_STORAGE_CLIENT] = function (Container $container) {
            return new GoogleTagManagerToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function () {
            return $this->getStore();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore(): Store
    {
        return Store::getInstance();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductFieldMapperPlugins(Container $container): Container
    {
        $container[static::PRODUCT_FIELD_MAPPER_PLUGINS] = function () {
            return $this->getProductFieldMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\ProductFieldMapperPluginInterface[]
     */
    protected function getProductFieldMapperPlugins(): array
    {
        return [
            new IdProductFieldMapperPlugin(),
            new NameProductFieldMapperPlugin(),
            new VariantProductFieldMapperPlugin(),
            new BrandProductFieldMapperPlugin(),
            new Dimension10ProductFieldMapperPlugin(),
            new QuantityProductFieldMapperPlugin(),
            new PriceProductFieldMapperPlugin(),
            new CouponProductFieldMapperPlugin(),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNewsletterControllerEventHandler(Container $container): Container
    {
        $container[static::NEWSLETTER_SUBSCRIBE_EVENT_HANDLER] = function (Container $container) {
            return $this->getNewsletterControllerEventHandler($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return array
     */
    protected function getNewsletterControllerEventHandler(Container $container): array
    {
        return [
            new NewsletterSubscribeEventHandler($container[static::GTM_SESSION_HANDLER]),
            new NewsletterConfirmationEventHandler($container[static::GTM_SESSION_HANDLER]),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGoogleTagManagerSessionHandler(Container $container): Container
    {
        $container[static::GTM_SESSION_HANDLER] = function (Container $container) {
            return new GoogleTagManagerSessionHandler($container[static::SESSION_CLIENT]);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addEnhancedEcommerceSessionHandler(Container $container): Container
    {
        $container[static::EEC_SESSION_HANDLER] = function (Container $container) {
            return new EnhancedEcommerceSessionHandler(
                $container[static::SESSION_CLIENT],
                $container[static::CART_CLIENT],
                $container[static::EEC_PRODUCT_MAPPER_PLUGIN]
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addEnhancedEcommerceProductMapperPlugin(Container $container): Container
    {
        $container[static::EEC_PRODUCT_MAPPER_PLUGIN] = function (Container $container) {
            return new EnhancedEcommerceProductMapperPlugin($container[static::PRODUCT_FIELD_MAPPER_PLUGINS]);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartControllerEventHandler(Container $container): Container
    {
        $container[static::CART_CONTROLLER_EVENT_HANDLER] = function (Container $container) {
            return $this->getCartControllerEventHandler($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface[]
     */
    protected function getCartControllerEventHandler(Container $container): array
    {
        return [
            new AddProductControllerEventHandler($container[static::EEC_SESSION_HANDLER]),
            new ChangeQuantityProductControllerEventHandler($container[static::EEC_SESSION_HANDLER], $container[static::CART_CLIENT]),
            new RemoveProductControllerEventHandler($container[static::EEC_SESSION_HANDLER], $container[static::CART_CLIENT]),
            new PlaceOrderControllerEventHandler($container[static::EEC_SESSION_HANDLER], $container[static::CART_CLIENT]),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductImageStorageClient(Container $container): Container
    {
        $container[static::PRODUCT_IMAGE_STORAGE_CLIENT] = function (Container $container) {
            return new GoogleTagManagerToProductImageStorageClientBridge($container->getLocator()->productImageStorage()->client());
        };

        return $container;
    }
}
