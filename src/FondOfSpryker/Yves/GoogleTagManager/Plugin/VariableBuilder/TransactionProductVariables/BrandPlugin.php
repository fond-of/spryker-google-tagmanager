<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class BrandPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const BRAND = 'brand';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array
    {
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (!isset($product->getAbstractAttributes()[$locale]['brand'])) {
            return [];
        }

        return [static::BRAND => $product->getAbstractAttributes()[$locale]['brand']];
    }
}
