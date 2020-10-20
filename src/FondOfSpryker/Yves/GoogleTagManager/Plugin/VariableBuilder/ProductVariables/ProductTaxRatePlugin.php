<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Exception;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductTaxRatePlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
{
    use LoggerTrait;

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
        try {
            $gooleTagManagerProductDetailTransfer->setProductTaxRate($product->getTaxRate());
        } catch (Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s',
                $product::TAX_RATE,
                self::class
            ), ['product' => json_encode($product)]);
        }

        return $gooleTagManagerProductDetailTransfer;
    }
}
