<?php


namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;


use Generated\Shared\Transfer\ProductViewTransfer;

interface GoogleTagManagerClientToProductStorageClientInterface
{
    /**
     * @param array $data
     * @param $localeName
     * @param array $selectedAttributes
     *
     * @return ProductViewTransfer
     */
    public function mapProductStorageData(array $data, $localeName, array $selectedAttributes = []): ProductViewTransfer;
}
