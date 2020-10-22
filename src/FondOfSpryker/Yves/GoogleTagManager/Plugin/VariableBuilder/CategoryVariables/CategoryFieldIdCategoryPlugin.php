<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryFieldIdCategoryPlugin extends AbstractPlugin implements CategoryFieldPluginInterface
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
        if (isset($category['id_category'])) {
            $gooleTagManagerCategoryTransfer->setIdCategory($category['id_category']);
        }

        return $gooleTagManagerCategoryTransfer;
    }
}
