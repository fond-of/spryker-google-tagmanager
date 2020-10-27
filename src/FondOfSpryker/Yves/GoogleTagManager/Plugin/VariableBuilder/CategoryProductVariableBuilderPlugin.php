<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryProductVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CategoryProductVariableBuilderPlugin extends AbstractPlugin implements CategoryProductVariableBuilderPluginInterface
{
    use LoggerTrait;

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer $gooleTagManagerCategoryTransfer
     * @param array $productArray
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer
     */
    public function getProduct(
        GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer,
        array $productArray
    ): GooleTagManagerCategoryProductTransfer {
        $gooleTagManagerCategoryProductTransfer = $this->createGooleTagManagerCategoryProductTransfer();

        foreach ($this->getFactory()->getCategoryProductVariableBuilderFieldPlugins() as $plugin) {
            try {
                $gooleTagManagerCategoryProductTransfer = $plugin->handle(
                    $gooleTagManagerCategoryProductTransfer,
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

        return $gooleTagManagerCategoryProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer
     */
    protected function createGooleTagManagerCategoryProductTransfer(): GooleTagManagerCategoryProductTransfer
    {
        return new GooleTagManagerCategoryProductTransfer();
    }
}
