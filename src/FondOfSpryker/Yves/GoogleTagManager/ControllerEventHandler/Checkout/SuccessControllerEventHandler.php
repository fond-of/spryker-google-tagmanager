<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Checkout;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class SuccessControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'successAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void
    {
        $quoteTransfer = $client->getCartClient()->getQuote();

        $request->getSession()->set(GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE, $quoteTransfer);
    }
}
