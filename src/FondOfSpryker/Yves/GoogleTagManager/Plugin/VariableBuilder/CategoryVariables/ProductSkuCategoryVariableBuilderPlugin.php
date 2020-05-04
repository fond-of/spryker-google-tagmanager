<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;

class ProductSkuCategoryVariableBuilderPlugin implements CategoryVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     *
     * @return array
     */
    public function handle(GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer): array
    {
        $products = [];
        $skus = [];

        foreach ($gooleTagManagerCategoryTransfer->getCategoryProducts() as $product) {
            $sku = \str_replace('ABSTRACT-', '', strtoupper($product->getSku()));
            $product->setSku($sku);

            $skus[] = $sku;
            $products[] = $product->toArray();
        }

        return [
            'products' => $skus,
            'categoryProducts' => $products,
        ];
    }
}
