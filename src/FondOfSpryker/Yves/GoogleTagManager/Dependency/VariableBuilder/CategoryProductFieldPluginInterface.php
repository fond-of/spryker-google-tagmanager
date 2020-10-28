<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;

interface CategoryProductFieldPluginInterface
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
    ): GoogleTagManagerCategoryProductTransfer;
}
