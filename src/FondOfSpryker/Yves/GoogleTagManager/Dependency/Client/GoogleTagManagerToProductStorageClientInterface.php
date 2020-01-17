<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

interface GoogleTagManagerToProductStorageClientInterface
{
    /**
     * @param $idProductAbstract
     * @param $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData($idProductAbstract, $localeName): ?array;
}
