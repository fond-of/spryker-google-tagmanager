<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;


use Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface;

class GoogleTagManagerToProductResourceAliasStorageClientBridge implements GoogleTagManagerToProductResourceAliasStorageClientInterface
{
    /**
     * @var ProductResourceAliasStorageClientInterface
     */
    protected $storageClient;

    public function __construct(ProductResourceAliasStorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->storageClient->findProductAbstractStorageDataBySku($sku, $localeName);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function getProductConcreteStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->storageClient->getProductConcreteStorageDataBySku($sku, $localeName);
    }
}
