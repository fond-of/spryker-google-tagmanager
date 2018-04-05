<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use Pyz\Yves\Cart\CartFactory;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class GoogleTagManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const CART_CLIENT = 'CART_CLIENT';
    const SALES_CLIENT = 'CART_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->provideCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return $container
     */
    protected function provideCartClient(Container $container)
    {
        $container[static::CART_CLIENT] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        return $container;
    }
}
