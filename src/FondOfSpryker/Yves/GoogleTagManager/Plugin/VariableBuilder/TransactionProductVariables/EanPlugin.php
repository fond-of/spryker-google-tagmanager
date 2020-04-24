<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class EanPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const EAN = 'ean';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array
    {
        if (isset($product->getAbstractAttributes()['_'][static::EAN])) {
            return [
                static::EAN => $product->getAbstractAttributes()['_'][static::EAN],
            ];
        }

        return [];
    }
}
