<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductFieldNamePlugin extends AbstractPlugin implements ProductFieldPluginInterface
{
    public const NAME_UNTRANSLATED = 'name_untranslated';

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer
     */
    public function handle(
        GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GoogleTagManagerProductDetailTransfer {
        $googleTagManagerProductDetailTransfer->setProductName($product->getName());

        if ($this->hasNameUntranslated($product) === true) {
            $googleTagManagerProductDetailTransfer->setProductName($product->getAttributes()[static::NAME_UNTRANSLATED]);
        }

        return $googleTagManagerProductDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    protected function hasNameUntranslated(ProductAbstractTransfer $productAbstractTransfer): bool
    {
        $attributes = $productAbstractTransfer->getAttributes();

        if (!isset($attributes[static::NAME_UNTRANSLATED])) {
            return false;
        }

        if (empty($attributes[static::NAME_UNTRANSLATED])) {
            return false;
        }

        return true;
    }
}
