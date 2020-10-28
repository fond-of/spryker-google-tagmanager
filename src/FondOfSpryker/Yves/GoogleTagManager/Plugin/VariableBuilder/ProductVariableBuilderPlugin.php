<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductVariableBuilderInterface;
use Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer;
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
        $googleTagManagerProductDetailTransfer = $this->createGoogleTagManagerProductDetailTransfer();
        $productVariableBuilderPlugins = $this->getFactory()->getProductVariableBuilderPlugins();

        foreach ($productVariableBuilderPlugins as $plugin) {
            try {
                $googleTagManagerProductDetailTransfer = $plugin->handle($googleTagManagerProductDetailTransfer, $product);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        return $this->stripEmptyArrayIndex($googleTagManagerProductDetailTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer
     */
    protected function createGoogleTagManagerProductDetailTransfer(): GoogleTagManagerProductDetailTransfer
    {
        return new GoogleTagManagerProductDetailTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer): array
    {
        $googleTagManagerProductDetailArray = $googleTagManagerProductDetailTransfer->toArray(true, true);

        foreach ($googleTagManagerProductDetailArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($googleTagManagerProductDetailArray[$field]);
            }
        }

        return $googleTagManagerProductDetailArray;
    }
}
