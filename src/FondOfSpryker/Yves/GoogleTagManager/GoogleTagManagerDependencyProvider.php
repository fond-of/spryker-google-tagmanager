<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use Pyz\Yves\Cart\CartFactory;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class GoogleTagManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const CART_CLIENT = 'CART_CLIENT';
    const PRODUCT_CLIENT = 'PRODUCT_CLIENT';
    const CATEGORY_CLIENT = 'CATEGORY_CLIENT';

    const CART_FACTORY  = 'CART_FACTORY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->provideCartClient($container);
        $this->provideProductClient($container);
        $this->provideCategoryClient($container);

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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return $container
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
     * @return $container
     */
    protected function provideCategoryClient(Container $container)
    {
        $container[static::CATEGORY_CLIENT] = function (Container $container) {
            return $container->getLocator()->category()->client();
        };

        return $container;
    }
}
