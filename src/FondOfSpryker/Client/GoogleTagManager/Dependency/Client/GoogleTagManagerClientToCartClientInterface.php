<?php


namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface GoogleTagManagerClientToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
