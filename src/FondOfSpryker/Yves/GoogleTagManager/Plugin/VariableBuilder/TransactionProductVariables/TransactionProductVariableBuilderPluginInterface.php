<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

interface TransactionProductVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array;
}
