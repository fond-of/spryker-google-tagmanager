<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;

class ProductSkuCategoryVariableBuilderPlugin implements CategoryVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer
     *
     * @return array
     */
    public function handle(GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer): array
    {
        $products = [];
        $skus = [];

        foreach ($googleTagManagerCategoryTransfer->getCategoryProducts() as $product) {
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
