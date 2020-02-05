<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'addAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void
    {
        $sku = $request->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU);
        $quantity = $request->get(EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY);

        if (!$sku) {
            return;
        }

        if (!$quantity) {
            $quantity = 1;
        }

        $enhancedEcommerceProductData = new EnhancedEcommerceProductDataTransfer();
        $enhancedEcommerceProductData->setSku($sku);
        $enhancedEcommerceProductData->setQuantity($quantity);

        $sessionHandler = $this->getFactory()->createEnhancedEcommerceSessionHandler();
        $sessionHandler->addProduct($enhancedEcommerceProductData);

        return;
    }
}
