<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;

interface CategoryProductFieldPluginInterface
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
    ): GooleTagManagerCategoryProductTransfer;
}
