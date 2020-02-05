<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class Dimension1ProductFieldMapperPlugin implements ProductFieldMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     *
     * @return void
     */
    public function map(ProductViewTransfer $productViewTransfer, EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer): void
    {
        $attributes = $productViewTransfer->getAttributes();

        if (!\is_array($attributes) || \count($attributes) === 0) {
            return;
        }

        if (isset($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_SIZE_UNTRANSLATED])) {
            $enhancedEcommerceProductTransfer->setDimension1($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_SIZE_UNTRANSLATED]);

            return;
        }

        if (isset($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_SIZE])) {
            $enhancedEcommerceProductTransfer->setDimension1($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_SIZE]);

            return;
        }
    }
}
