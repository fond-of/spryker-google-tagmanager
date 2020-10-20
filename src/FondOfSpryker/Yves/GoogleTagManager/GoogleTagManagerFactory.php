<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultDataLayerVariableBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductDataLayerVariableBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\NewsletterVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\OrderVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\QuoteVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\TransactionProductsVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\TransactionProductsVariableBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductArrayModel;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductModelBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariableBuilder;
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
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductDataLayerVariableBuilderInterface
     */
    public function getProductVariableBuilder(): ProductDataLayerVariableBuilderInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\CategoryVariableBuilder
     */
    public function createCategoryVariableBuilder(): CategoryVariableBuilder
    {
        return new CategoryVariableBuilder(
            $this->getMoneyPlugin(),
            $this->getCategoryVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultDataLayerVariableBuilderInterface
     */
    public function getDefaultVariableBuilderPlugin(): DefaultDataLayerVariableBuilderInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::DEFAULT_VARIABLE_BUILDER_PLUGIN);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\OrderVariableBuilder
     */
    public function createOrderVariableBuilder(): OrderVariableBuilder
    {
        return new OrderVariableBuilder(
            $this->getMoneyPlugin(),
            $this->getCartClient(),
            $this->getProductStorageClient(),
            $this->getStore(),
            $this->getOrderVariableBuilderPlugins(),
            $this->createTransactionProductsVariableBuilder()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\QuoteVariableBuilder
     */
    public function createQuoteVariableBuilder(): QuoteVariableBuilder
    {
        return new QuoteVariableBuilder(
            $this->getMoneyPlugin(),
            $this->getQuoteVariableBuilderPlugins(),
            $this->createTransactionProductsVariableBuilder()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\TransactionProductsVariableBuilderInterface
     */
    protected function createTransactionProductsVariableBuilder(): TransactionProductsVariableBuilderInterface
    {
        return new TransactionProductsVariableBuilder(
            $this->getMoneyPlugin(),
            $this->getProductStorageClient(),
            $this->getProductImageStorageClient(),
            $this->getTransactionProductVariableBuilderPlugins(),
            $this->getStore()->getCurrentLocale()
        );
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
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductVariableBuilderPluginInterface[]
     */
    public function getProductVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    public function getCategoryVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    public function getDefaultVariableBuilderFieldPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::DEFAULT_VARIABLE_BUILDER_FIELD_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderVariableBuilderPluginInterface[]
     */
    public function getOrderVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::ORDER_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[]
     */
    public function getQuoteVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::QUOTE_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @return array
     */
    public function getTransactionProductVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::TRANSACTION_PRODUCT_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables\NewsletterVariablesPluginInterface[]
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
