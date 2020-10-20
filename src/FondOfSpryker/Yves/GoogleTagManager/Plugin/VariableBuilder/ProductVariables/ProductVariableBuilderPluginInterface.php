<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer
     */
    public function handle(
        GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GooleTagManagerProductDetailTransfer;
}
