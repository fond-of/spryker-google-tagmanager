<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryProductFieldSkuPlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
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
        if (isset($productArray['abstract_sku'])) {
            $sku = str_replace('ABSTRACT-', '', strtoupper($productArray['abstract_sku']));
            $gooleTagManagerCategoryProductTransfer->setSku($sku);
        }

        return $gooleTagManagerCategoryProductTransfer;
    }
}
