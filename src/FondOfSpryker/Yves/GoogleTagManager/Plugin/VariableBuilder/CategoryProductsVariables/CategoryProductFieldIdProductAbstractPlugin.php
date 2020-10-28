<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryProductFieldIdProductAbstractPlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
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
        if (isset($productArray['id_product_abstract'])) {
            $googleTagManagerCategoryProductTransfer->setIdProductAbstract($productArray['id_product_abstract']);
        }

        return $googleTagManagerCategoryProductTransfer;
    }
}
