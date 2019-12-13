<?php


namespace FondOfSpryker\Client\GoogleTagManager;

use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageBridge;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class GoogleTagManagerDependencyProvider extends AbstractDependencyProvider
{
    public const PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT = 'PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT';
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addProductResourceAliasStorageClient($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductResourceAliasStorageClient(Container $container): Container
    {
        $container[static::PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT] = function (Container $container) {
            return new GoogleTagManagerClientToProductResourceAliasStorageBridge(
                $container->getLocator()->productResourceAliasStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
}
