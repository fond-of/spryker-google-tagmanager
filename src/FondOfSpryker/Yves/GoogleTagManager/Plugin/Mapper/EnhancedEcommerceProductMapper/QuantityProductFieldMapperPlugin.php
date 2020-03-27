<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class QuantityProductFieldMapperPlugin implements ProductFieldMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     * @param array $params
     *
     * @return void
     */
    public function map(
        ProductViewTransfer $productViewTransfer,
        EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer,
        array $params
    ): void
    {
        if ($productViewTransfer->getQuantity() > 0) {
            $enhancedEcommerceProductTransfer->setQuantity($productViewTransfer->getQuantity());
        }
    }
}
