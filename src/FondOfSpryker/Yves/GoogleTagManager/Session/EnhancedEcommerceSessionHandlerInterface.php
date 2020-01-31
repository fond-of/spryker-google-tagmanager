<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\ProductViewTransfer;

interface EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @param bool $removeFromSessionAfterOutput
     *
     * @return array
     */
    public function getAddProductEventArray(bool $removeFromSessionAfterOutput = false): array;

    /**
     * @param bool $removeFromSessionAfterOutput
     * @return array
     */
    public function getChangeProductQuantityEventArray(bool $removeFromSessionAfterOutput = false): array;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1): void;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quanity
     *
     * @return void
     */
    public function changeProductQuantity(ProductViewTransfer $productViewTransfer, int $quanity = 1): void;

    /**
     * @param ProductViewTransfer $productViewTransfer
     */
    public function removeProduct(ProductViewTransfer $productViewTransfer): void;
}
