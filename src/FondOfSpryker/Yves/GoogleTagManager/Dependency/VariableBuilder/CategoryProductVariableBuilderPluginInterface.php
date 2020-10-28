<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;

interface CategoryProductVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer $googleTagManagerCategoryTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer
     */
    public function getProduct(
        GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer,
        array $productArray
    ): GoogleTagManagerCategoryProductTransfer;
}
