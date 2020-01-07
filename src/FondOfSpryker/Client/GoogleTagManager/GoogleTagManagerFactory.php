<?php


namespace FondOfSpryker\Client\GoogleTagManager;

use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToCartClientInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class GoogleTagManagerFactory extends AbstractFactory
{
    /**
     * @throws
     *
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface
     */
    public function getProductResourceAliasStorageClient(): GoogleTagManagerClientToProductResourceAliasStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface
     */
    public function getProductStorageClient(): GoogleTagManagerClientToProductStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToCartClientInterface
     */
    public function getCartClient(): GoogleTagManagerClientToCartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CLIENT);
    }
}
