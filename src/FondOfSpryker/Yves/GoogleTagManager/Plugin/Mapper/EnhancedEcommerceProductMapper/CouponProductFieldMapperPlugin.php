<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class CouponProductFieldMapperPlugin implements ProductFieldMapperPluginInterface
{
    public const FIELD_NAME = 'discountCodes';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     * @param array $params
     *
     * @return void
     */
    public function map(ProductViewTransfer $productViewTransfer, EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer, array $params): void
    {
        if (!isset($params[static::FIELD_NAME])) {
            return;
        }

        if (!is_array($params[static::FIELD_NAME])) {
            return;
        }

        $enhancedEcommerceProductTransfer->setCoupon(rtrim(implode(',', $params[static::FIELD_NAME]), ','));
    }
}
