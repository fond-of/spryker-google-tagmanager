<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\ProductViewTransfer;

interface EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAddProductEventArray(string $name);

    /**
     * @param bool $removeFromSessionAfterRendering
     *
     * @return string|null
     */
    public function renderAddProductToCartViewJson(bool $removeFromSessionAfterRendering = true): ?string;

    /**
     * @param ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return void
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1): void;
}
