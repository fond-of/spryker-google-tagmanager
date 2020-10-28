<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;

interface CategoryFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer
     * @param array $category
     * @param array $products
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer
     */
    public function handle(
        GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer,
        array $category,
        array $products = [],
        array $params = []
    ): GoogleTagManagerCategoryTransfer;
}
