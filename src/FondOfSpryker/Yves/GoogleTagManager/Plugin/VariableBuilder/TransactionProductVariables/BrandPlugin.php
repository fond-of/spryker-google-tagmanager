<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class BrandPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const FIELD_NAME = 'brand';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (isset($itemTransfer->getAbstractAttributes()['_'][static::FIELD_NAME])) {
            return [static::FIELD_NAME => $itemTransfer->getAbstractAttributes()['_'][static::FIELD_NAME]];
        }

        if (isset($itemTransfer->getAbstractAttributes()[$locale][static::FIELD_NAME])) {
            return [static::FIELD_NAME => $itemTransfer->getAbstractAttributes()[$locale][static::FIELD_NAME]];
        }

        return [];
    }
}
