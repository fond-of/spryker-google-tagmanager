<?php


namespace FondOfSpryker\Client\GoogleTagManager;


use Generated\Shared\Transfer\ProductViewTransfer;

interface GoogleTagManagerClientInterface
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

    /**
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return ProductViewTransfer
     */
    public function mapProductStorageData(array $data, string $localeName, array $selectedAttributes = []): ProductViewTransfer;
}
