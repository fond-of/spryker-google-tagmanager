<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ChangeQuantityProductControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'changeAction';
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

        if (!$sku || !$quantity) {
            return;
        }

        $itemTransfer = $this->getProductFromQuote($sku);

        if ($itemTransfer === null) {
            return;
        }

        $sessionHandler = $this->getFactory()->createEnhancedEcommerceSessionHandler();

        $enhancedEcommerceProductData = new EnhancedEcommerceProductDataTransfer();
        $enhancedEcommerceProductData->setProductAbstractId($itemTransfer->getIdProductAbstract());
        $enhancedEcommerceProductData->setSku($sku);
        $enhancedEcommerceProductData->setPrice($itemTransfer->getUnitPrice());

        if ($quantity > $itemTransfer->getQuantity()) {
            $enhancedEcommerceProductData->setQuantity($quantity - $itemTransfer->getQuantity());

            $sessionHandler->addProduct($enhancedEcommerceProductData);

            return;
        }

        if ($quantity < $itemTransfer->getQuantity()) {
            $enhancedEcommerceProductData->setQuantity($itemTransfer->getQuantity() - $quantity);

            $sessionHandler->removeProduct($enhancedEcommerceProductData);

            return;
        }
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getProductFromQuote(string $sku): ?ItemTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
