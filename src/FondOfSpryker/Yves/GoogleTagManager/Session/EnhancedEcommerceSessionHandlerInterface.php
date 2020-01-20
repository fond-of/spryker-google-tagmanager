<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\ProductViewTransfer;

interface EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @param array $eventArray
     */
    public function setAddProductEvent(array $eventArray): void;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAddProductEvent(string $name);

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return mixed
     */
    public function addProductToAddProductEvent(ProductViewTransfer $productViewTransfer, int $quantity = 1);
}
