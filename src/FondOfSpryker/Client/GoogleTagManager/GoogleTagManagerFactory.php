<?php


namespace FondOfSpryker\Client\GoogleTagManager;


use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class GoogleTagManagerFactory extends AbstractFactory
{
    /**
     * @return GoogleTagManagerClientToProductResourceAliasStorageInterface
     *
     * @throws
     */
    public function getProductResourceAliasStorageClient(): GoogleTagManagerClientToProductResourceAliasStorageInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_RESOURCE_ALIAS_STORAGE_CLIENT);
    }

    /**
     * @return GoogleTagManagerClientToProductStorageClientInterface
     *
     * @throws
     */
    public function getProductStorageClient(): GoogleTagManagerClientToProductStorageClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }
}
