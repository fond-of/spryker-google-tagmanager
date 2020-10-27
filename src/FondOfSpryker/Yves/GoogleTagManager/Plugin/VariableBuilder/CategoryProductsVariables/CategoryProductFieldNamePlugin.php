<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryProductFieldNamePlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
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
        if (!array_key_exists('attributes', $productArray)) {
            $gooleTagManagerCategoryProductTransfer->setName($productArray['abstract_name']);
        }

        if (isset($productArray['attributes']['name_untranslated'])) {
            $gooleTagManagerCategoryProductTransfer->setName($productArray['attributes']['name_untranslated']);
        }

        return $gooleTagManagerCategoryProductTransfer;
    }
}
