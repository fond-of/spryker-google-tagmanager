<?php


namespace FondOfSpryker\Client\GoogleTagManager;

use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface;
use FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface;

interface GoogleTagManagerClientInterface
{
    /**
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductStorageClientInterface
     */
    public function getProductStorageClient(): GoogleTagManagerClientToProductStorageClientInterface;

    /**
     * @return \FondOfSpryker\Client\GoogleTagManager\Dependency\Client\GoogleTagManagerClientToProductResourceAliasStorageClientInterface
     */
    public function getProductResourceAliasStorageClient(): GoogleTagManagerClientToProductResourceAliasStorageClientInterface;
}
