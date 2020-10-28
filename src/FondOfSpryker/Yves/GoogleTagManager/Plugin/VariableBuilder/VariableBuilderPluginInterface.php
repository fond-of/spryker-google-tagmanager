<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface VariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return array
     */
    public function handle(ProductAbstractTransfer $product): array;
}
