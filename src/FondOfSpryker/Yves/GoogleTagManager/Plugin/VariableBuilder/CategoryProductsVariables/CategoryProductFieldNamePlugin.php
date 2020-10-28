<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryProductsVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryProductFieldNamePlugin extends AbstractPlugin implements CategoryProductFieldPluginInterface
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
    ): GoogleTagManagerCategoryProductTransfer {
        if (!array_key_exists('attributes', $productArray)) {
            $googleTagManagerCategoryProductTransfer->setName($productArray['abstract_name']);
        }

        if (isset($productArray['attributes']['name_untranslated'])) {
            $googleTagManagerCategoryProductTransfer->setName($productArray['attributes']['name_untranslated']);
        }

        return $googleTagManagerCategoryProductTransfer;
    }
}
