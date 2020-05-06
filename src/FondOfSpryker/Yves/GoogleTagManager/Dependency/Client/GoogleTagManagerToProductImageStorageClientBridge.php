<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use FondOfSpryker\Client\ProductImageStorage\ProductImageStorageClientInterface;
use Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface;

class GoogleTagManagerToProductImageStorageClientBridge implements GoogleTagManagerToProductImageStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\ProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * CartPageToProductImageStorageBridge constructor.
     *
     * @param \FondOfSpryker\Client\ProductImageStorage\ProductImageStorageClientInterface $productImageStorageClient
     */
    public function __construct(ProductImageStorageClientInterface $productImageStorageClient)
    {
        $this->productImageStorageClient = $productImageStorageClient;
    }

    public function getProductAbstractImageStorageReader(): ProductAbstractImageStorageReaderInterface
    {
        return $this->productImageStorageClient->getProductAbstractImageStorageReader();
    }
}
