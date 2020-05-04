<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class VariantProductFieldMapperPlugin implements ProductFieldMapperPluginInterface
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
    ): void {
        $attributes = $productViewTransfer->getAttributes();

        if (!\is_array($attributes) || \count($attributes) === 0) {
            return;
        }

        if (isset($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_STYLE_UNTRANSLATED])) {
            $enhancedEcommerceProductTransfer->setVariant($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_STYLE_UNTRANSLATED]);

            return;
        }

        if (isset($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_STYLE])) {
            $enhancedEcommerceProductTransfer->setVariant($attributes[EnhancedEcommerceConstants::PRODUCT_FIELD_ATTRIBUTE_STYLE]);

            return;
        }
    }
}
