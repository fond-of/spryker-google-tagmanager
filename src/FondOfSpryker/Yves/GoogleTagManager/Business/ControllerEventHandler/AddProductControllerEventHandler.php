<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use Generated\Shared\Transfer\ProductViewTransfer;
use Symfony\Component\HttpFoundation\Request;

class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'addAction';
    }

    /**
     * @param Request $request
     */
    public function hande(Request $request, GoogleTagManagerClientInterface $client): void
    {
        $sku = $request->get('sku');

        $product = $client->findProductConcreteStorageDataBySku($sku, 'en_US');
        //$productViewTransfer = (new ProductViewTransfer())->fromArray($product);

        return;
    }
}
