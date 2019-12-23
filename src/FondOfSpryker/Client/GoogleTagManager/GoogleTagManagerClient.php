<?php

namespace FondOfSpryker\Client\GoogleTagManager;

use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToCartClientInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class GoogleTagManagerClient extends AbstractClient implements GoogleTagManagerClientInterface
{
    /**
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface
     */
    public function getProductStorageClient(): GoogleTagManagerClientToProductStorageClientInterface
    {
        return $this->getFactory()->getProductStorageClient();
    }

    /**
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface
     */
    public function getProductResourceAliasStorageClient(): GoogleTagManagerClientToProductResourceAliasStorageClientInterface
    {
        return $this->getFactory()->getProductResourceAliasStorageClient();
    }

    /**
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToCartClientInterface
     */
    public function getCartClient(): GoogleTagManagerClientToCartClientInterface
    {
        return $this->getFactory()->getCartClient();
    }
}
