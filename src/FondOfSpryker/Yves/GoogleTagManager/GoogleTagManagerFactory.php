<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\CategoryVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\DefaultVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\OrderVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\ProductVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\QuoteVariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

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
            $this->createCartClient(),
            $this->createSessionClient()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\ProductVariableBuilder
     */
    protected function createProductVariableBuilder(): ProductVariableBuilder
    {
        return new ProductVariableBuilder(
            $this->createMoneyPlugin(),
            $this->createTaxProductConnectorClient(),
            $this->getProductVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\CategoryVariableBuilder
     */
    protected function createCategoryVariableBuilder(): CategoryVariableBuilder
    {
        return new CategoryVariableBuilder(
            $this->createMoneyPlugin(),
            $this->getCategoryVariableBuilderPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\DefaultVariableBuilder
     */
    protected function createDefaultVariableBuilder(): DefaultVariableBuilder
    {
        return new DefaultVariableBuilder($this->getDefaultVariableBuilderPlugins());
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\OrderVariableBuilder
     */
    protected function createOrderVariableBuilder(): OrderVariableBuilder
    {
        return new OrderVariableBuilder(
            $this->createMoneyPlugin(),
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
     * @return \Spryker\Client\Cart\CartClientInterface CartClientInterface
     */
    protected function createCartClient(): CartClientInterface
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
     * @return \Spryker\Client\product\ProductClientInterface
     */
    protected function createProductClient(): ProductClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_CLIENT);
    }

    /**
     * @throws
     *
     * @return \Spryker\Client\Session\SessionClientInterface;
     */
    protected function createSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient
     */
    public function createTaxProductConnectorClient()
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
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\OrderVariableBuilderPluginInterface[]
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
}
