<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientBridge;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class EnhancedEcommerceSessionHandler implements EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface
     */
    protected $productMapper;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientBridge
     */
    protected $cartClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface $sessionClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientBridge $cartClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface $productMapper
     */
    public function __construct(
        GoogleTagManagerToSessionClientInterface $sessionClient,
        GoogleTagManagerToCartClientBridge $cartClient,
        EnhancedEcommerceProductMapperInterface $productMapper
    ) {
        $this->sessionClient = $sessionClient;
        $this->productMapper = $productMapper;
        $this->cartClient = $cartClient;
    }

    /**
     * @param array $eventArray
     *
     * @return void
     */
    protected function setAddProductEventArray(array $eventArray): void
    {
        $this->sessionClient->set(GoogleTagManagerConstants::EEC_EVENT_ADD, $eventArray);
    }

    /**
     * @param array $eventArray
     *
     * @return void
     */
    protected function setChangeProductQuantityEventArray(array $eventArray): void
    {
        $this->sessionClient->set(GoogleTagManagerConstants::EEC_EVENT_CHANGE_QUANTITY, $eventArray);
    }

    /**
     * @param bool $removeFromSessionAfterOutput
     *
     * @return array
     */
    public function getAddProductEventArray(bool $removeFromSessionAfterOutput = false): array
    {
        if (!\is_array($this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_ADD))) {
            return [];
        }

        $eventArray = $this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if ($removeFromSessionAfterOutput === true) {
            $this->sessionClient->remove(GoogleTagManagerConstants::EEC_EVENT_ADD);
        }

        return $eventArray;
    }

    /**
     * @param bool $removeFromSessionAfterOutput
     * @return array
     */
    public function getChangeProductQuantityEventArray(bool $removeFromSessionAfterOutput = false): array
    {
        if (!\is_array($this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_CHANGE_QUANTITY))) {
            return [];
        }

        $eventArray = $this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_CHANGE_QUANTITY);

        if ($removeFromSessionAfterOutput === true) {
            $this->sessionClient->remove(GoogleTagManagerConstants::EEC_EVENT_CHANGE_QUANTITY);
        }

        return $eventArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1): void
    {
        $eecProductAddEvent = $this->getAddProductEventArray(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if (!isset($eecProductAddEvent['ecommerce'])) {
            $eecProductAddEvent = $this->createAddProductEventArray();
        }

        $newProduct = $this->productMapper->map(array_merge(
            $productViewTransfer->toArray(),
            [GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => $quantity]
        ));

        \array_push($eecProductAddEvent['ecommerce']['add']['products'], $newProduct->toArray());

        $this->setAddProductEventArray($eecProductAddEvent);

        return;
    }

    /**
     * @param string $sku
     * @param int $quanity
     *
     * @return void
     */
    public function changeProductQuantity(ProductViewTransfer $productViewTransfer, int $quanity = 1): void
    {
        $itemTransfer = $this->getProductFromQuote($productViewTransfer->getSku());

        if ($itemTransfer === null) {
            return;
        }

        if ($itemTransfer->getQuantity() < $quanity) {
            $this->increaseProductQuantity($productViewTransfer, $quanity - $itemTransfer->getQuantity());

            return;
        }

        if ($itemTransfer->getQuantity() > $quanity) {
            $this->reduceProductQuantity($productViewTransfer, $itemTransfer->getQuantity() - $quanity);

            return;
        }
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function removeProduct(ProductViewTransfer $productViewTransfer): void
    {
        $itemTransfer = $this->getProductFromQuote($productViewTransfer->getSku());

        if ($itemTransfer === null) {
            return;
        }

        $this->reduceProductQuantity($productViewTransfer, $itemTransfer->getQuantity());
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function getProductFromQuote(string $sku): ?ItemTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($quoteTransfer->getItems() as $product) {
            if ($product->getSku() === $sku) {
                return $product;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return void
     */
    protected function increaseProductQuantity(ProductViewTransfer $productViewTransfer, int $quantity): void
    {
        $eecProductAddEvent = $this->getAddProductEventArray();

        if (!isset($eecProductAddEvent['ecommerce'])) {
            $eecProductAddEvent = $this->createAddProductEventArray();
        }

        $productViewTransfer->setQuantity($quantity);
        $enhancedEcommerceProductTransfer = $this->productMapper->map($productViewTransfer->toArray());

        \array_push($eecProductAddEvent['ecommerce']['add']['products'], $enhancedEcommerceProductTransfer->toArray());

        $this->setChangeProductQuantityEventArray($eecProductAddEvent);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return void
     */
    protected function reduceProductQuantity(ProductViewTransfer $productViewTransfer, int $quantity): void
    {
        $productViewTransfer->setQuantity($quantity);

        $enhancedEcommerceProductTransfer = $this->productMapper->map($productViewTransfer->toArray());

        $eecProductRemoveEvent = $this->createRemoveProductEventArray();
        \array_push($eecProductRemoveEvent['ecommerce']['remove']['products'], $enhancedEcommerceProductTransfer->toArray());

        $this->setChangeProductQuantityEventArray($eecProductRemoveEvent);
    }

    /**
     * @return array
     */
    protected function createAddProductEventArray(): array
    {
        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_ADD);
        $enhancedEcommerceTransfer->setEcommerce([
            'add' => [
                'actionField' => ['list' => 'Shopping cart'],
                'products' => [],
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function createRemoveProductEventArray(): array
    {
        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_REMOVE);
        $enhancedEcommerceTransfer->setEcommerce([
            'remove' => [
                'actionField' => ['list' => 'Shopping cart'],
                'products' => [],
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }
}
