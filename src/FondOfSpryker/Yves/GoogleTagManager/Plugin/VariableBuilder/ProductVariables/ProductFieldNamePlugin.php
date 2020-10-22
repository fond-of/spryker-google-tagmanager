<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductFieldNamePlugin extends AbstractPlugin implements ProductFieldPluginInterface
{
    public const NAME_UNTRANSLATED = 'name_untranslated';

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer
     */
    public function handle(
        GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GooleTagManagerProductDetailTransfer {
        $gooleTagManagerProductDetailTransfer->setProductName($product->getName());

        if ($this->hasNameUntranslated($product) === true) {
            $gooleTagManagerProductDetailTransfer->setProductName($product->getAttributes()[static::NAME_UNTRANSLATED]);
        }

        return $gooleTagManagerProductDetailTransfer;
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
