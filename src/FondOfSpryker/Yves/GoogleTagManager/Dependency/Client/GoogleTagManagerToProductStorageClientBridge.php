<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class GoogleTagManagerToProductStorageClientBridge implements GoogleTagManagerToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    public function __construct(ProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
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
        return $this->productStorageClient->mapProductStorageData($data, $localeName, $selectedAttributes);
    }
}
