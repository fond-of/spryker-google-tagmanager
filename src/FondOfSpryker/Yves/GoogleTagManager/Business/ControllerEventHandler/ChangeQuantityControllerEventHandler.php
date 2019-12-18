<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangeQuantityControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'changeAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client): void
    {
        $sku = $request->get('sku');
        $quantity = $request->get('quantity');
    }
}
