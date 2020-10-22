<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CategoryProductFieldPricePlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer $gooleTagManagerCategoryProductTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer
     */
    public function handle(
        GooleTagManagerCategoryProductTransfer $gooleTagManagerCategoryProductTransfer,
        array $productArray
    ): GooleTagManagerCategoryProductTransfer {
        if (isset($productArray['price'])) {
            $price = $this->getFactory()
                ->getMoneyPlugin()
                ->convertIntegerToDecimal($productArray['price']);

            $gooleTagManagerCategoryProductTransfer->setPrice($price);
        }

        return $gooleTagManagerCategoryProductTransfer;
    }
}
