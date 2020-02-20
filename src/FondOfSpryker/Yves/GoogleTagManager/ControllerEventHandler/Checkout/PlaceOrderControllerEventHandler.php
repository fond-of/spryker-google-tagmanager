<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Checkout;

use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class PlaceOrderControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface
     */
    protected $sessionHandler;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface
     */
    protected $cartClient;

    public const METHOD_NAME = 'placeOrderAction';

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface $sessionHandler
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface $cartClient
     */
    public function __construct(
        EnhancedEcommerceSessionHandlerInterface $sessionHandler,
        GoogleTagManagerToCartClientInterface $cartClient
    ) {
        $this->sessionHandler = $sessionHandler;
        $this->cartClient = $cartClient;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return static::METHOD_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void
    {
        $purchase = $this->sessionHandler->getPurchase();

        $this->sessionHandler->setPurchase(\array_merge(
            $purchase,
            ['shipment' => $this->getShippingCost()]
        ));
    }

    /**
     * @return int
     */
    protected function getShippingCost(): int
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if ($quoteTransfer->getShipment()) {
            if ($quoteTransfer->getTotals() === null) {
                return 0;
            }

            if (!$quoteTransfer->getShipment() === null) {
                return 0;
            }

            if (!$quoteTransfer->getShipment()->getMethod() === null) {
                return 0;
            }

            return $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        }

        return 0;
    }
}
