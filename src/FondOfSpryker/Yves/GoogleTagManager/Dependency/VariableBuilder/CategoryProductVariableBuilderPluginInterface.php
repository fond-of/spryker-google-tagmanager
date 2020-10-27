<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;

interface CategoryProductVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer $gooleTagManagerCategoryTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer
     */
    public function getProduct(
        GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer,
        array $productArray
    ): GooleTagManagerCategoryProductTransfer;
}
