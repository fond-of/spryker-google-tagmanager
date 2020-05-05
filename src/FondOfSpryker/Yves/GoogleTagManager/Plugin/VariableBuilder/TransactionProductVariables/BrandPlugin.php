<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class BrandPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const BRAND = 'brand';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (!isset($itemTransfer->getAbstractAttributes()[$locale]['brand'])) {
            return [];
        }

        return [static::BRAND => $itemTransfer->getAbstractAttributes()[$locale]['brand']];
    }
}
