<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;
use Symfony\Component\HttpFoundation\Request;

class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
    public const METHOD_NAME = 'addAction';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface
     */
    protected $sessionHandler;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface $sessionHandler
     */
    public function __construct(EnhancedEcommerceSessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
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
     * @param string|null $locale
     *
     * @return void
     */
    public function handle(Request $request, ?string $locale): void
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

        $this->sessionHandler->addProduct($enhancedEcommerceProductData);
    }
}
