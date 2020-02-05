<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface EnhancedEcommerceProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function map(ProductViewTransfer $productViewTransfer): EnhancedEcommerceProductTransfer;
}
