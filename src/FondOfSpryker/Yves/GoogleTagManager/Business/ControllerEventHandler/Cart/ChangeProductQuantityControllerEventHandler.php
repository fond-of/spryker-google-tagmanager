<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\Cart;


use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\ControllerEventHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangeProductQuantityControllerEventHandler implements ControllerEventHandlerInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void
    {
        $quoteTransfer = $client->getCartClient()->getQuote();
        $sku = $request->get('sku');
        $quantity = $request->get('quantity');

        return;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'changeAction';
    }
}
