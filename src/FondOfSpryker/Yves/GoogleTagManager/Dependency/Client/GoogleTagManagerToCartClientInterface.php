<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface GoogleTagManagerToCartClientInterface
{
    /**
     * @return QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
