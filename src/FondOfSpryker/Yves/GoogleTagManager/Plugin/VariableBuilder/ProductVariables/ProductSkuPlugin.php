<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductSkuPlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
{
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
        try {
            return $gooleTagManagerProductDetailTransfer->setProductSku($product->getSku());
        } catch (\Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s', 'sku', __CLASS__
            ), ['product' => json_encode($product)]);
        }
    }
}
