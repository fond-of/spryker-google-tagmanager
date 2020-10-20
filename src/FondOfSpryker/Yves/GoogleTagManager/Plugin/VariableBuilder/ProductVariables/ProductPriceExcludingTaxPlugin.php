<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductPriceExcludingTaxPlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
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
            $price = $this->getFactory()
                ->getTaxProductConnectorClient()
                ->getNetPriceForProduct($product);

            $priceExcludingTax = $this->getFactory()
                ->getMoneyPlugin()
                ->convertIntegerToDecimal($price);

            return $gooleTagManagerProductDetailTransfer->setProductPriceExcludingTax($priceExcludingTax);
        } catch (\Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s', $product::PRICE, __CLASS__
            ), ['product' => json_encode($product)]);
        }
    }
}
