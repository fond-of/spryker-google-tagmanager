<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface EnhancedEcommerceProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function map(ProductViewTransfer $productViewTransfer, array $params = []): EnhancedEcommerceProductTransfer;
}
