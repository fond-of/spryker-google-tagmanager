<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <jozsef.geng@fondof.de>
 */

namespace FondOfSpryker\Yves\GoogleTagManager;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

class GoogleTagManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const CART_CLIENT = 'CART_CLIENT';
    const SALES_CLIENT = 'CART_CLIENT';
    const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->provideCartClient($container);
        $this->provideMoneyPlugin($container);

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
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideMoneyPlugin(Container $container)
    {
        $container[static::PLUGIN_MONEY] = function () {
            return new MoneyPlugin();
        };
        return $container;
    }
}
