<?php

namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;

use Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface;

class GoogleTagManagerClientToProductResourceAliasStorageClientBridge implements GoogleTagManagerClientToProductResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface
     */
    protected $client;

    /**
     * GoogleTagManagerClientToProductResourceAliasStorageBridge constructor.
     *
     * @param \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface $client
     */
    public function __construct(ProductResourceAliasStorageClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->client->findProductAbstractStorageDataBySku($sku, $localeName);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->client->getProductConcreteStorageDataBySku($sku, $localeName);
    }
}
