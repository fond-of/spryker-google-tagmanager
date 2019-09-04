<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface VariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return array
     */
    public function handle(ProductAbstractTransfer $product): array;
}
