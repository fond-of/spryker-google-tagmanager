<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryFieldNamePlugin extends AbstractPlugin implements CategoryFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     * @param array $category
     * @param array $products
     *
     * @param array $params
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer
     */
    public function handle(
        GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer,
        array $category,
        array $products = [],
        array $params = []
    ): GooleTagManagerCategoryTransfer
    {
        if (isset($category['name'])) {
            $gooleTagManagerCategoryTransfer->setName($category['name']);
        }

        return $gooleTagManagerCategoryTransfer;
    }
}
