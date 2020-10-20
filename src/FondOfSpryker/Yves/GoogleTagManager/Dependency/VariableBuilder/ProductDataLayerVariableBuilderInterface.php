<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductDataLayerVariableBuilderInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return array
     */
    public function getVariables(ProductAbstractTransfer $product): array;
}
