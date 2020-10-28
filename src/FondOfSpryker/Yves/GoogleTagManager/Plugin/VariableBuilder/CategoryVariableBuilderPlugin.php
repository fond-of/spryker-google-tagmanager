<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;
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
        $googleTagManagerCategoryTransfer = $this->createGoogleTagManagerCategoryTransfer();
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
        GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer,
        array $products
    ): GoogleTagManagerCategoryTransfer {
        foreach ($products as $product) {
            $googleTagManagerCategoryProductTransfer = $this->getFactory()
                ->getCategoryProductVariableBuilderPlugin()
                ->getProduct($googleTagManagerCategoryTransfer, $product);

            $googleTagManagerCategoryTransfer->addCategoryProducts($googleTagManagerCategoryProductTransfer);
        }

        return $googleTagManagerCategoryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer
     */
    protected function createGoogleTagManagerCategoryTransfer(): GoogleTagManagerCategoryTransfer
    {
        return new GoogleTagManagerCategoryTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer $googleTagManagerCategoryTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer): array
    {
        $googleTagManagerCategoryArray = $googleTagManagerCategoryTransfer->toArray(true, true);

        foreach ($googleTagManagerCategoryArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($googleTagManagerCategoryArray[$field]);
            }
        }

        return $googleTagManagerCategoryArray;
    }
}
