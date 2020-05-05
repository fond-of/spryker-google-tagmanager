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
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (isset($product->getAbstractAttributes()[$locale][static::EAN])) {
            return [
                static::EAN => $product->getAbstractAttributes()[$locale][static::EAN],
            ];
        }

        return [];
    }
}