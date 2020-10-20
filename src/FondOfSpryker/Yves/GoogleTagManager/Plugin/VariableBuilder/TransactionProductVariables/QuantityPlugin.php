<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class QuantityPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const FIELD_NAME = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        if ($itemTransfer->getQuantity() > 0) {
            return [static::FIELD_NAME => $itemTransfer->getQuantity()];
        }

        return [];
    }
}
