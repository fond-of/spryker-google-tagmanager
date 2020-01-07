<?php


namespace FondOfSpryker\Client\GoogleTagManager\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;

class GoogleTagManagerClientToCartClientBridge implements GoogleTagManagerClientToCartClientInterface
{
    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     */
    public function __construct(CartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer
    {
        return $this->cartClient->getQuote();
    }
}
