<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer
     */
    public function handle(
        GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GoogleTagManagerProductDetailTransfer;
}
