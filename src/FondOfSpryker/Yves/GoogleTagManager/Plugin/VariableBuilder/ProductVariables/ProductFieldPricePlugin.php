<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductFieldPricePlugin extends AbstractPlugin implements ProductFieldPluginInterface
{
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
        $price = $this->getFactory()
            ->getMoneyPlugin()
            ->convertIntegerToDecimal($product->getPrice());

        return $googleTagManagerProductDetailTransfer->setProductPrice($price);
    }
}
