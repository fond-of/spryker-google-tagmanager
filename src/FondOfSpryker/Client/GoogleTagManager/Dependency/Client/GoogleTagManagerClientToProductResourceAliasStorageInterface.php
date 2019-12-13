<?php

namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;

interface GoogleTagManagerClientToProductResourceAliasStorageInterface
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
    public function findProductConcreteStorageDataBySku(string $sku, string $localeName): ?array;
}
