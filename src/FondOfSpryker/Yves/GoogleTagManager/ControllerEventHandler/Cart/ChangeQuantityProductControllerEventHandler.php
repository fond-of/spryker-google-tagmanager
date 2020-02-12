<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;


class ChangeQuantityProductControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @var EnhancedEcommerceSessionHandlerInterface
     */
    protected $sessionHandler;

    /**
     * @var GoogleTagManagerToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param EnhancedEcommerceSessionHandlerInterface $sessionHandler
     * @param GoogleTagManagerToCartClientInterface $cartClient
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

        if ((int)$quantity === $itemTransfer->getQuantity()) {
            return;
        }

        $enhancedEcommerceProductData = new EnhancedEcommerceProductDataTransfer();
        $enhancedEcommerceProductData->setProductAbstractId($itemTransfer->getIdProductAbstract());
        $enhancedEcommerceProductData->setSku($sku);
        $enhancedEcommerceProductData->setPrice($itemTransfer->getUnitPrice());

        if ($quantity > $itemTransfer->getQuantity()) {
            $enhancedEcommerceProductData->setQuantity($quantity - $itemTransfer->getQuantity());

            $this->sessionHandler->addProduct($enhancedEcommerceProductData);

            return;
        }

        if ($quantity < $itemTransfer->getQuantity()) {
            $enhancedEcommerceProductData->setQuantity($itemTransfer->getQuantity() - $quantity);

            $this->sessionHandler->removeProduct($enhancedEcommerceProductData);

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
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
