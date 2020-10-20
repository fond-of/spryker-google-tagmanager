<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductTaxPlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
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
            $productAbstract = $this->getFactory()
                ->getTaxProductConnectorClient()
                ->getTaxAmountForProduct($product);

            if ($productAbstract->getTaxAmount() > 0) {
                $tax = $this->getFactory()->getMoneyPlugin()->convertIntegerToDecimal(
                    $productAbstract->getTaxAmount()
                );

                return $gooleTagManagerProductDetailTransfer->setProductTax($tax);
            }

            return $gooleTagManagerProductDetailTransfer->setProductTax(0);
        } catch (\Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: something went wrong in %s', __CLASS__
            ), ['product' => json_encode($product)]);
        }
    }
}
