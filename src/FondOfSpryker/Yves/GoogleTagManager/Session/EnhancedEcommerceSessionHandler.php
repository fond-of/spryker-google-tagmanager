<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
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
    public function setAddProductEvent(array $eventArray): void
    {
        $this->sessionClient->set(GoogleTagManagerConstants::EEC_EVENT_ADD, $eventArray);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAddProductEvent(string $name)
    {
        return $this->sessionClient->get(GoogleTagManagerConstants::EEC_EVENT_ADD);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1)
    {
        if ($this->containsAddProductEventProduct($productViewTransfer->getSku())) {
            $this->increaseProductQuantityInAddProductEvent($productViewTransfer->getSku();

            return;
        }

        $eecProductAddEvent = $this->getAddProductEvent(GoogleTagManagerConstants::EEC_EVENT_ADD);
        $newProduct = $this->createProduct(array_merge(
            $productViewTransfer->toArray(),
            [GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => $quantity]
        ));

        \array_push($eecProductAddEvent['ecommerce']['add']['products'], $newProduct);

        $this->setAddProductEvent($eecProductAddEvent);

        return;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function containsAddProductEventProduct(string $sku): bool
    {
        $eecProductAddEvent = $this->getAddProductEvent(GoogleTagManagerConstants::EEC_EVENT_ADD);

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
        $eecProductAddEvent = $this->getAddProductEvent(GoogleTagManagerConstants::EEC_EVENT_ADD);

        if (!$eecProductAddEvent) {
            return false;
        }

        if (!isset($eecProductAddEvent['ecommerce']['add']['products'])) {
            return false;
        }

        if (count($eecProductAddEvent['ecommerce']['add']['products'] === 0)) {
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
}
