<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

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
     * @param $idProductAbstract
     * @param $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData($idProductAbstract, $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
    }
}
