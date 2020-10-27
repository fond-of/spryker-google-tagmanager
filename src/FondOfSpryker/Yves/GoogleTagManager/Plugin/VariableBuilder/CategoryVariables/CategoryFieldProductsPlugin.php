<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryFieldProductsPlugin extends AbstractPlugin implements CategoryFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     * @param array $category
     * @param array $products
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer
     */
    public function handle(
        GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer,
        array $category,
        array $products = [],
        array $params = []
    ): GooleTagManagerCategoryTransfer {
        foreach ($gooleTagManagerCategoryTransfer->getCategoryProducts() as $gooleTagManagerCategoryProductTransfer) {
            $gooleTagManagerCategoryTransfer->addProducts($gooleTagManagerCategoryProductTransfer->getSku());
        }

        return $gooleTagManagerCategoryTransfer;
    }
}
