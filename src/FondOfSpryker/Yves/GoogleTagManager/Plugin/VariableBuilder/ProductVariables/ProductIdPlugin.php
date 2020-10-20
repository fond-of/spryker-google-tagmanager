<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Exception;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

class ProductIdPlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
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
            $gooleTagManagerProductDetailTransfer->setProductId($product->getIdProductAbstract());
        } catch (Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s',
                $product::ID_PRODUCT_ABSTRACT,
                self::class
            ), ['product' => json_encode($product)]);
        }

        return $gooleTagManagerProductDetailTransfer;
    }
}
