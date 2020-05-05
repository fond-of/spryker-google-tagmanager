<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface;

interface GoogleTagManagerToProductImageStorageClientInterface
{
    /**
     * @return \Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface
     */
    public function getProductAbstractImageStorageReader(): ProductAbstractImageStorageReaderInterface;
}
