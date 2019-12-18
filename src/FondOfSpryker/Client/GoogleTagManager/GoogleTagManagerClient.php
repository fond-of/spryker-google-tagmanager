<?php

namespace FondOfSpryker\Client\GoogleTagManager;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class GoogleTagManagerClient extends AbstractClient implements GoogleTagManagerClientInterface
{
    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->getFactory()
            ->getProductResourceAliasStorageClient()
            ->findProductAbstractStorageDataBySku($sku, $localeName);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataBySku(string $sku, string $localeName): ?array
    {
        return $this->getFactory()
            ->getProductResourceAliasStorageClient()
            ->findProductConcreteStorageDataBySku($sku, $localeName);
    }

    /**
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(array $data, string $localeName, array $selectedAttributes = []): ProductViewTransfer
    {
        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($data, $localeName, $selectedAttributes);
    }
}
