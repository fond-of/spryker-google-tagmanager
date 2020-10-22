<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductFieldTaxPlugin extends AbstractPlugin implements ProductFieldPluginInterface
{
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
        $productAbstract = $this->getFactory()
            ->getTaxProductConnectorClient()
            ->getTaxAmountForProduct($product);

        if ($productAbstract->getTaxAmount() > 0) {
            $tax = $this->getFactory()->getMoneyPlugin()->convertIntegerToDecimal(
                $productAbstract->getTaxAmount()
            );

            $gooleTagManagerProductDetailTransfer->setProductTax($tax);
        }

        return $gooleTagManagerProductDetailTransfer;
    }
}
