<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductNamePlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
{
    public const NAME_UNTRANSLATED = 'name_untranslated';

    use LoggerTrait;

    /**
     * @param GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer
     * @param ProductAbstractTransfer $product
     * @param array $params
     *
     * @return GooleTagManagerProductDetailTransfer
     */
    public function handle(
        GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GooleTagManagerProductDetailTransfer
    {
        if(isset($product->getAttributes()[static::NAME_UNTRANSLATED]) && !empty($product->getAttributes()[static::NAME_UNTRANSLATED])) {
            return $gooleTagManagerProductDetailTransfer->setProductName($product->getAttributes()[static::NAME_UNTRANSLATED]);
        }

        try {
            return $gooleTagManagerProductDetailTransfer->setProductName($product->getName());
        } catch (\Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s', 'name', __CLASS__
            ), ['product' => json_encode($product)]);
        }

    }
}
