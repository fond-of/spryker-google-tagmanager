<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ProductVariableBuilderPlugin extends AbstractPlugin implements ProductVariableBuilderInterface
{
    use LoggerTrait;

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
            try {
                $gooleTagManagerProductDetailTransfer = $plugin->handle($gooleTagManagerProductDetailTransfer, $product);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
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
