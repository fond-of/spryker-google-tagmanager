<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductResourceAliasStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\CategoryVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\DefaultVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\OrderVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\ProductVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\QuoteVariableBuilder;
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
        return new GoogleTagManagerTwigExtension(
            $this->getContainerID(),
            $this->isEnabled(),
            $this->getVariableBuilders(),
            $this->getCartClient(),
            $this->getSessionClient()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\ProductVariableBuilder
     */
    protected function createProductVariableBuilder(): ProductVariableBuilder
    {
        return new ProductVariableBuilder(
            $this->createMoneyPlugin(),
            $this->getTaxProductConnectorClient(),
            $this->getProductVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\CategoryVariableBuilder
     */
    protected function createCategoryVariableBuilder(): CategoryVariableBuilder
    {
        return new CategoryVariableBuilder(
            $this->getClient(),
            $this->createMoneyPlugin(),
            $this->getStore()->getCurrentLocale(),
            $this->getCategoryVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\DefaultVariableBuilder
     */
    protected function createDefaultVariableBuilder(): DefaultVariableBuilder
    {
        return new DefaultVariableBuilder(
            $this->getDefaultVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\OrderVariableBuilder
     */
    protected function createOrderVariableBuilder(): OrderVariableBuilder
    {
        return new OrderVariableBuilder(
            $this->createMoneyPlugin(),
            $this->getCartClient(),
            $this->getOrderVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\QuoteVariableBuilder
     */
    protected function createQuoteVariableBuilder(): QuoteVariableBuilder
    {
        return new QuoteVariableBuilder(
            $this->createMoneyPlugin(),
            $this->getQuoteVariableBuilderPlugins()
        );
    }

    /**
     * @return array
     */
    public function getVariableBuilders(): array
    {
        return [
            GoogleTagManagerConstants::PAGE_TYPE_PRODUCT => $this->createProductVariableBuilder(),
            GoogleTagManagerConstants::PAGE_TYPE_CATEGORY => $this->createCategoryVariableBuilder(),
            GoogleTagManagerConstants::PAGE_TYPE_DEFAULT => $this->createDefaultVariableBuilder(),
            GoogleTagManagerConstants::PAGE_TYPE_ORDER => $this->createOrderVariableBuilder(),
            GoogleTagManagerConstants::PAGE_TYPE_QUOTE => $this->createQuoteVariableBuilder(),
        ];
    }

    /**
     * @return \Twig\Extension\ExtensionInterface
     */
    public function createEnhancedEcommerceTwigExtension(): ExtensionInterface
    {
        return new EnhancedEcommerceTwigExtension($this->getEnhancedEcommercePlugins());
    }

    /**
     * @throws
     *
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
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface
     */
    public function getCartClient(): GoogleTagManagerToCartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CLIENT);
    }

    /**
     * @throws
     *
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function createMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface
     */
    protected function getSessionClient(): GoogleTagManagerToSessionClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient
     */
    public function getTaxProductConnectorClient()
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::TAX_PRODUCT_CONNECTOR_CLIENT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\VariableBuilderPluginInterface[]
     */
    public function getProductVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    public function getCategoryVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CATEGORY_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    public function getDefaultVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::DEFAULT_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderVariableBuilderPluginInterface[]
     */
    public function getOrderVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::ORDER_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[]
     */
    public function getQuoteVariableBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::QUOTE_VARIABLE_BUILDER_PLUGINS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface[]
     */
    public function getCartControllerEventHandler(): array
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CONTROLLER_EVENT_HANDLER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return Store::getInstance();
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface
     */
    public function getEnhancedEcommerceProductMapperPlugin(): EnhancedEcommerceProductMapperInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::EEC_PRODUCT_MAPPER_PLUGIN);
    }

    /**
     * @return GoogleTagManagerToProductStorageClientInterface
     *
     * @throws
     */
    public function getProductStorageClient(): GoogleTagManagerToProductStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return GoogleTagManagerToProductResourceAliasStorageClientInterface
     *
     * @throws
     */
    public function getProductResourceAliasStorageClient(): GoogleTagManagerToProductResourceAliasStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT);
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
}
