<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class EanPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const EAN = 'ean';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (isset($itemTransfer->getAbstractAttributes()[$locale][static::EAN])) {
            return [
                static::EAN => $itemTransfer->getAbstractAttributes()[$locale][static::EAN],
            ];
        }

        return [];
    }
}
