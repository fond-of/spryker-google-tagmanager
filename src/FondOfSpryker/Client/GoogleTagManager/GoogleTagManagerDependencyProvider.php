<?php


namespace FondOfSpryker\Client\GoogleTagManager;

use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToCartClientBridge;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientBridge;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class GoogleTagManagerDependencyProvider extends AbstractDependencyProvider
{
    public const PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT = 'PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT';
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const CART_CLIENT = 'CART_CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addProductResourceAliasStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductResourceAliasStorageClient(Container $container): Container
    {
        $container[static::PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT] = function (Container $container) {
            return new GoogleTagManagerClientToProductResourceAliasStorageClientBridge(
                $container->getLocator()->productResourceAliasStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::PRODUCT_STORAGE_CLIENT] = function (Container $container) {
            return new GoogleTagManagerClientToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CART_CLIENT] = function (Container $container) {
            return new GoogleTagManagerClientToCartClientBridge(
                $container->getLocator()->cart()->client()
            );
        };

        return $container;
    }
}
