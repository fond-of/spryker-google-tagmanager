<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CategoryProductFieldPricePlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer $googleTagManagerCategoryProductTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer
     */
    public function handle(
        GoogleTagManagerCategoryProductTransfer $googleTagManagerCategoryProductTransfer,
        array $productArray
    ): GoogleTagManagerCategoryProductTransfer {
        if (isset($productArray['price'])) {
            $price = $this->getFactory()
                ->getMoneyPlugin()
                ->convertIntegerToDecimal($productArray['price']);

            $googleTagManagerCategoryProductTransfer->setPrice($price);
        }

        return $googleTagManagerCategoryProductTransfer;
    }
}
