<?php


namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class GoogleTagManagerClientToProductStorageClientBridge implements GoogleTagManagerClientToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $client;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $client
     */
    public function __construct(ProductStorageClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     * @param $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(array $data, $localeName, array $selectedAttributes = []): ProductViewTransfer
    {
        return $this->client->mapProductStorageData($data, $localeName, $selectedAttributes);
    }
}
