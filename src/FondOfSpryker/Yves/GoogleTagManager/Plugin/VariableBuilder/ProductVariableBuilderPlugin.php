<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductDataLayerVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductVariableBuilderPlugin extends AbstractPlugin implements ProductDataLayerVariableBuilderInterface
{
    public const VARIABLE_BUILDER_NAME = 'product';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return array
     */
    public function getVariables(ProductAbstractTransfer $product): array
    {
        $gooleTagManagerProductDetailTransfer = $this->createGooleTagManagerProductDetailTransfer();
        $productVariableBuilderPlugins = $this->getFactory()->getProductVariableBuilderPlugins();

        foreach ($productVariableBuilderPlugins as $plugin) {
            $gooleTagManagerProductDetailTransfer = $plugin->handle(
                $gooleTagManagerProductDetailTransfer,
                $product
            );
        }

        return $this->stripEmptyArrayIndex($gooleTagManagerProductDetailTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer
     */
    protected function createGooleTagManagerProductDetailTransfer(): GooleTagManagerProductDetailTransfer
    {
        return new GooleTagManagerProductDetailTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GooleTagManagerProductDetailTransfer $gooleTagManagerProductDetailTransfer): array
    {
        $gooleTagManagerProductDetailArray = $gooleTagManagerProductDetailTransfer->toArray(true, true);

        foreach ($gooleTagManagerProductDetailArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($gooleTagManagerProductDetailArray[$field]);
            }
        }

        return $gooleTagManagerProductDetailArray;
    }
}
