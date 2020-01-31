<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;


interface GoogleTagManagerToProductResourceAliasStorageClientInterface
{
    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataBySku(string $sku, string $localeName): ?array;

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function getProductConcreteStorageDataBySku(string $sku, string $localeName): ?array;
}
