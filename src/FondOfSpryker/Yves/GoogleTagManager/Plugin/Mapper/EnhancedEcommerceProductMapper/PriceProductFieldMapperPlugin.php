<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class PriceProductFieldMapperPlugin extends AbstractPlugin implements ProductFieldMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     *
     * @return void
     */
    public function map(ProductViewTransfer $productViewTransfer, EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer): void
    {
        if (!$productViewTransfer->getPrice()) {
            return;
        }

        $price = $this->getFactory()
            ->createMoneyPlugin()
            ->convertIntegerToDecimal($productViewTransfer->getPrice());

        $enhancedEcommerceProductTransfer->setPrice($price);
    }
}
