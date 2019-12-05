<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <jozsefgeng@86gmail.com>
 */

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderDiscountPlugin;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\SalePricePlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

/**
 * @package FondOfSpryker\Yves\GoogleTagManager
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CART_CLIENT = 'CART_CLIENT';
    public const PRODUCT_CLIENT = 'PRODUCT_CLIENT';
    public const TAX_PRODUCT_CONNECTOR_CLIENT = 'TAX_PRODUCT_CONNECTOR_CLIENT';
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';
    public const PLUGIN_DATA_LAYER_VARIABLE = 'PLUGIN_DATA_LAYER_VARIABLE';
    public const SESSION_CLIENT = 'SESSION_CLIENT';
    public const VARIABLE_BUILDER_PLUGINS = 'VARIABLE_BUILDER_PLUGINS';
    public const PRODUCT_VARIABLE_BUILDER_PLUGINS = 'PRODUCT_VARIABLE_BUILDER_PLUGINS';
    public const DEFAULT_VARIABLE_BUILDER_PLUGINS = 'DEFAULT_VARIABLE_BUILDER_PLUGINS';
    public const CATEGORY_VARIABLE_BUILDER_PLUGINS = 'CATEGORY_VARIABLE_BUILDER_PLUGINS';
    public const ORDER_VARIABLE_BUILDER_PLUGINS = 'ORDER_VARIABLE_BUILDER_PLUGINS';
    public const QUOTE_VARIABLE_BUILDER_PLUGINS = 'QUOTE_VARIABLE_BUILDER_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->provideCartClient($container);
        $this->provideProductClient($container);
        $this->provideTaxProductConnectorClient($container);
        $this->provideMoneyPlugin($container);
        $this->provideSessionClient($container);
        $this->addProductVariableBuilderPlugins($container);
        $this->addCategoryVariableBuilderPlugins($container);
        $this->addDefaultVariableBuilderPlugins($container);
        $this->addOrderVariableBuilderPlugins($container);
        $this->addQuoteVariableBuilderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function provideCartClient(Container $container)
    {
        $container[static::CART_CLIENT] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container $container
     */
    protected function provideProductClient(Container $container)
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
    protected function provideTaxProductConnectorClient(Container $container)
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
    protected function provideMoneyPlugin(Container $container)
    {
        $container[static::PLUGIN_MONEY] = function () {
            return new MoneyPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function provideSessionClient(Container $container)
    {
        $container[static::SESSION_CLIENT] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductVariableBuilderPlugins(Container $container): Container
    {
        $container[static::PRODUCT_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getProductVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductVariableBuilderPluginInterface[]
     */
    protected function getProductVariableBuilderPlugins(Container $container): array
    {
        return [
            new SalePricePlugin(new MoneyPlugin(), $this->getConfig()),
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCategoryVariableBuilderPlugins(Container $container): Container
    {
        $container[static::CATEGORY_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getCategoryVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    protected function getCategoryVariableBuilderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addDefaultVariableBuilderPlugins(Container $container): Container
    {
        $container[static::DEFAULT_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getDefaultVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    protected function getDefaultVariableBuilderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addOrderVariableBuilderPlugins(Container $container): Container
    {
        $container[static::ORDER_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getOrderVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderVariableBuilderPluginInterface[]
     */
    protected function getOrderVariableBuilderPlugins(Container $container): array
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
        $container[static::QUOTE_VARIABLE_BUILDER_PLUGINS] = function (Container $container) {
            return $this->getQuoteVariableBuilderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[]
     */
    protected function getQuoteVariableBuilderPlugins(Container $container): array
    {
        return [];
    }
}
