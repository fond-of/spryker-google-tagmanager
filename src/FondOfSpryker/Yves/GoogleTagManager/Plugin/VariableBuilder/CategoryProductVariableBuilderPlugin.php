<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CategoryProductVariableBuilderPlugin extends AbstractPlugin implements CategoryProductVariableBuilderPluginInterface
{
    use LoggerTrait;

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer $googleTagManagerCategoryTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer
     */
    public function getProduct(
        GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer,
        array $productArray
    ): GoogleTagManagerCategoryProductTransfer {
        $googleTagManagerCategoryProductTransfer = $this->createGoogleTagManagerCategoryProductTransfer();

        foreach ($this->getFactory()->getCategoryProductVariableBuilderFieldPlugins() as $plugin) {
            try {
                $googleTagManagerCategoryProductTransfer = $plugin->handle(
                    $googleTagManagerCategoryProductTransfer,
                    $productArray
                );
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ));
            }
        }

        return $googleTagManagerCategoryProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer
     */
    protected function createGoogleTagManagerCategoryProductTransfer(): GoogleTagManagerCategoryProductTransfer
    {
        return new GoogleTagManagerCategoryProductTransfer();
    }
}
