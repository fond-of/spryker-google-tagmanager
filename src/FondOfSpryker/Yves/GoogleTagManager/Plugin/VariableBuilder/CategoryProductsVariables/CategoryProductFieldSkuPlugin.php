<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryProductFieldSkuPlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
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
        if (isset($productArray['abstract_sku'])) {
            $sku = str_replace('ABSTRACT-', '', strtoupper($productArray['abstract_sku']));
            $googleTagManagerCategoryProductTransfer->setSku($sku);
        }

        return $googleTagManagerCategoryProductTransfer;
    }
}
