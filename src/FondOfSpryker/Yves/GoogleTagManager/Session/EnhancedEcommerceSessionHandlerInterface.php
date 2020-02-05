<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;

interface EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @param bool $removeFromSession
     *
     * @return array
     */
    public function getAddedProducts($removeFromSession = false): array;

    /**
     * @param bool $removeFromSession
     *
     * @return array
     */
    public function getRemovedProducts($removeFromSession = false): array;

    /**
     * @param bool $removeFromSessionAfterOutput
     *
     * @return array
     */
    public function getChangeProductQuantityEventArray(bool $removeFromSessionAfterOutput = false): array;

    /**
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer $productDataTransfer
     *
     * @return void
     */
    public function addProduct(EnhancedEcommerceProductDataTransfer $productDataTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer $ecommerceProductDataTransfer
     *
     * @return void
     */
    public function changeProductQuantity(EnhancedEcommerceProductDataTransfer $ecommerceProductDataTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer $productDataTransfer
     *
     * @return void
     */
    public function removeProduct(EnhancedEcommerceProductDataTransfer $productDataTransfer): void;
}
