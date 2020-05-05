<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class QuantityPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const QUANTITY = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array
    {
        if ($product->getQuantity() > 0) {
            return [static::QUANTITY => $product->getQuantity()];
        }

        return [];
    }
}
