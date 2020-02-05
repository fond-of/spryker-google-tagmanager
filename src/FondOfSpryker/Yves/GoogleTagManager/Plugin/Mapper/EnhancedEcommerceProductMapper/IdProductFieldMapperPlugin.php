<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class IdProductFieldMapperPlugin implements ProductFieldMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     *
     * @return void
     */
    public function map(ProductViewTransfer $productViewTransfer, EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer): void
    {
        $enhancedEcommerceProductTransfer->setId($productViewTransfer->getSku());
    }
}
