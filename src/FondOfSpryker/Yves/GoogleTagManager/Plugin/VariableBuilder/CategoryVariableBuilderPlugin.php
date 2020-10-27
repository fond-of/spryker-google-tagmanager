<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CategoryVariableBuilderPlugin extends AbstractPlugin implements CategoryVariableBuilderPluginInterface
{
    /**
     * @param array $category
     * @param array $products
     * @param array $params
     *
     * @return array
     */
    public function getVariables(array $category, array $products, array $params = []): array
    {
        $googleTagManagerCategoryTransfer = $this->createGooleTagManagerCategoryTransfer();
        $googleTagManagerCategoryTransfer = $this->addProducts($googleTagManagerCategoryTransfer, $products);

        foreach ($this->getFactory()->getCategoryVariableBuilderFieldPlugins() as $plugin) {
            try {
                $googleTagManagerCategoryTransfer = $plugin->handle($googleTagManagerCategoryTransfer, $category, $products, $params);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ));
            }
        }

        return $this->stripEmptyArrayIndex($googleTagManagerCategoryTransfer);
    }

    /**
     * @return void
     */
    protected function addProducts(
        GooleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer,
        array $products
    ): GooleTagManagerCategoryTransfer {
        foreach ($products as $product) {
            $gooleTagManagerCategoryProductTransfer = $this->getFactory()
                ->getCategoryProductVariableBuilderPlugin()
                ->getProduct($googleTagManagerCategoryTransfer, $product);

            $googleTagManagerCategoryTransfer->addCategoryProducts($gooleTagManagerCategoryProductTransfer);
        }

        return $googleTagManagerCategoryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer
     */
    protected function createGooleTagManagerCategoryTransfer(): GooleTagManagerCategoryTransfer
    {
        return new GooleTagManagerCategoryTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer $gooleTagManagerCategoryTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer): array
    {
        $gooleTagManagerCategoryArray = $gooleTagManagerCategoryTransfer->toArray(true, true);

        foreach ($gooleTagManagerCategoryArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($gooleTagManagerCategoryArray[$field]);
            }
        }

        return $gooleTagManagerCategoryArray;
    }
}
