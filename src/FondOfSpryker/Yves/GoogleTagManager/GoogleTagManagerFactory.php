<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductVariableBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteVariableBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductsVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\NewsletterVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductArrayModel;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductModelBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandler;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use FondOfSpryker\Yves\GoogleTagManager\Twig\EnhancedEcommerceTwigExtension;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Twig\Extension\ExtensionInterface;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension
     */
    public function createGoogleTagManagerTwigExtension(): GoogleTagManagerTwigExtension
    {
        return new GoogleTagManagerTwigExtension();
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductVariableBuilderInterface
     */
    public function getProductVariableBuilder(): ProductVariableBuilderInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryVariableBuilderPluginInterface
     */
    public function getCategoryVariableBuilderPlugin(): CategoryVariableBuilderPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultVariableBuilderPluginInterface
     */
    public function getDefaultVariableBuilderPlugin(): DefaultVariableBuilderPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::DEFAULT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface[]
     */
    public function getCategoryProductVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderVariableBuilderPluginInterface
     */
    public function getOrderVariableBuilderPlugin(): OrderVariableBuilderPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::ORDER_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteVariableBuilderInterface
     */
    public function getQuoteVariableBuilderPlugin(): QuoteVariableBuilderInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::QUOTE_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductsVariableBuilderPluginInterface
     */
    public function getTransactionProductVariableBuilderPlugin(): TransactionProductsVariableBuilderPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::TRANSACTION_PRODUCT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\NewsletterVariableBuilder
     */
    public function getNewsletterVariableBuilder(): NewsletterVariableBuilder
    {
        return new NewsletterVariableBuilder($this->getNewsletterVariableBuilderPlugins());
    }

    /**
     * @return \Twig\Extension\ExtensionInterface
     */
    public function createEnhancedEcommerceTwigExtension(): ExtensionInterface
    {
        return new EnhancedEcommerceTwigExtension($this->getEnhancedEcommercePlugins());
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommercePageTypePluginInterface[]
     */
    public function getEnhancedEcommercePlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::ENHANCED_ECOMMERCE_PAGE_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig
     */
    public function getGoogleTagManagerConfig(): GoogleTagManagerConfig
    {
        return $this->getConfig();
    }

    /**
     * @return string
     */
    protected function getContainerID(): string
    {
        return $this->getConfig()->getContainerID();
    }

    /**
     * @return bool
     */
    protected function isEnabled(): bool
    {
        return $this->getConfig()->isEnabled();
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface
     */
    public function getCartClient(): GoogleTagManagerToCartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface
     */
    public function getSessionClient(): GoogleTagManagerToSessionClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient
     */
    public function getTaxProductConnectorClient()
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::TAX_PRODUCT_CONNECTOR_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductFieldVariableBuilderPluginInterface[]
     */
    public function getProductVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryFieldPluginInterface[]
     */
    public function getCategoryVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductVariableBuilderPluginInterface
     */
    public function getCategoryProductVariableBuilderPlugin(): CategoryProductVariableBuilderPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_PRODUCT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface[]
     */
    public function getDefaultVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::DEFAULT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface[]
     */
    public function getOrderVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::ORDER_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface[]
     */
    public function getQuoteVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::QUOTE_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface[]
     */
    public function getTransactionProductVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::TRANSACTION_PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables\NewsletterVariablesPluginInterfaceField[]
     */
    public function getNewsletterVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::NEWSLETTER_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface[]
     */
    public function getCartControllerEventHandler(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CONTROLLER_EVENT_HANDLER);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface[]
     */
    public function getNewsletterControllerEventHandler(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::NEWSLETTER_SUBSCRIBE_EVENT_HANDLER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::STORE);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface
     */
    public function getProductStorageClient(): GoogleTagManagerToProductStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface
     */
    public function createEnhancedEcommerceSessionHandler(): EnhancedEcommerceSessionHandlerInterface
    {
        return new EnhancedEcommerceSessionHandler(
            $this->getSessionClient(),
            $this->getCartClient(),
            $this->getEnhancedEcommerceProductMapperPlugin()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductModelBuilderInterface
     */
    public function createEnhancedEcommerceProductArrayBuilder(): ProductModelBuilderInterface
    {
        return new ProductArrayModel(
            $this->getCartClient(),
            $this->getProductStorageClient(),
            $this->getEnhancedEcommerceProductMapperPlugin(),
            $this->getConfig()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface
     */
    public function getEnhancedEcommerceProductMapperPlugin(): EnhancedEcommerceProductMapperInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::EEC_PRODUCT_MAPPER_PLUGIN);
    }

    /**
     * @return array
     */
    public function getPaymentMethodMappingConfig(): array
    {
        return $this->getConfig()->getPaymentMethodMapping();
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface
     */
    public function getProductImageStorageClient(): GoogleTagManagerToProductImageStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_IMAGE_STORAGE_CLIENT);
    }
}
