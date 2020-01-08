<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;


use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;

class GoogleTagManagerToCartClientBridge implements GoogleTagManagerToCartClientInterface
{
    /**
     * @var CartClientInterface
     */
    protected $cartClient;

    public function __construct(CartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return QuoteTransfer
     */
    public function getQuote(): QuoteTransfer
    {
        return $this->cartClient->getQuote();
    }
}
