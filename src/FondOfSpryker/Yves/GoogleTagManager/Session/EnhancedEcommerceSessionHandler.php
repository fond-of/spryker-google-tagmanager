<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
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
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface $sessionClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface $productMapper
     */
    public function __construct(
        GoogleTagManagerToSessionClientInterface $sessionClient,
        EnhancedEcommerceProductMapperInterface $productMapper
    ) {
        $this->sessionClient = $sessionClient;
        $this->productMapper = $productMapper;
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
     * @param string $name
     *
     * @return mixed
     */
    public function getAddProductEventArray(string $name)
    {
        return $this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_ADD);
    }

    /**
     * @param bool $removeFromSessionAfterRendering
     *
     * @return string|null
     */
    public function renderAddProductToCartViewJson(bool $removeFromSessionAfterRendering = true): ?string
    {
        $eecProductAddEvent = $this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if (!is_array($eecProductAddEvent)) {
            return null;
        }

        if ($removeFromSessionAfterRendering === true) {
            $this->sessionClient->remove(GoogleTagManagerConstants::EEC_EVENT_ADD);
        }

        return json_encode($eecProductAddEvent);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1): void
    {
        if ($this->containsAddProductEventProduct($productViewTransfer->getSku())) {
            $this->increaseProductQuantityInAddProductEvent($productViewTransfer->getSku());

            return;
        }

        $eecProductAddEvent = $this->getAddProductEventArray(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if ($eecProductAddEvent === null) {
            $eecProductAddEvent = $this->getEnhancedEcommerceAddProductEventArray();
        }

        $newProduct = $this->createProduct(array_merge(
            $productViewTransfer->toArray(),
            [GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => $quantity]
        ));

        \array_push($eecProductAddEvent['ecommerce']['add']['products'], $newProduct->toArray());

        $this->setAddProductEventArray($eecProductAddEvent);

        return;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function containsAddProductEventProduct(string $sku): bool
    {
        $eecProductAddEvent = $this->getAddProductEventArray(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if (!$eecProductAddEvent) {
            return false;
        }

        if (!isset($eecProductAddEvent['ecommerce']['add']['products'])) {
            return false;
        }

        foreach ($eecProductAddEvent['ecommerce']['add']['products'] as $index => $product) {
            if ($product[GoogleTagManagerConstants::EEC_PRODUCT_ID] === $sku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $sku
     * @param int $quanity
     *
     * @return bool
     */
    protected function increaseProductQuantityInAddProductEvent(string $sku, int $quanity = 1): bool
    {
        $eecProductAddEvent = $this->getAddProductEventArray(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if (!$eecProductAddEvent) {
            return false;
        }

        if (!isset($eecProductAddEvent['ecommerce']['add']['products'])) {
            return false;
        }

        if (count($eecProductAddEvent['ecommerce']['add']['products']) === 0) {
            return false;
        }

        foreach ($eecProductAddEvent['ecommerce']['add']['products'] as $index => $product) {
            if ($product[GoogleTagManagerConstants::EEC_PRODUCT_ID] === $sku) {
                $eecProductAddEvent['ecommerce']['add']['products'][$index][GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY] += $quanity;

                return true;
            }
        }

        return false;
    }

    /**
     * @param array $product
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    protected function createProduct(array $product): EnhancedEcommerceProductTransfer
    {
        return $this->productMapper->map($product);
    }

    /**
     * @return array
     */
    protected function getEnhancedEcommerceAddProductEventArray(): array
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
}
