<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
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
        $categoryProducts = [];

        $googleTagManagerCategoryTransfer = $this->createGooleTagManagerCategoryTransfer();

        foreach ($this->getFactory()->getCategoryVariableBuilderFieldPlugins() as $plugin) {
            try {
                $googleTagManagerCategoryTransfer = $plugin->handle($googleTagManagerCategoryTransfer, $category, $products);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ));
            }
        }

        /*$googleTagManagerCategoryTransfer->setIdCategory($category['id_category']);
        $googleTagManagerCategoryTransfer->setName($category['name']);
        $googleTagManagerCategoryTransfer->setSize(count($products));

        foreach ($products as $product) {
            $gooleTagManagerCategoryProductTransfer = new GooleTagManagerCategoryProductTransfer();
            $gooleTagManagerCategoryProductTransfer->setIdProductAbstract($product['id_product_abstract']);
            $gooleTagManagerCategoryProductTransfer->setName($this->getProductName($product));
            $gooleTagManagerCategoryProductTransfer->setSku($product['abstract_sku']);
            $gooleTagManagerCategoryProductTransfer->setPrice($this->moneyPlugin->convertIntegerToDecimal($product['price']));

            $googleTagManagerCategoryTransfer->addCategoryProducts($gooleTagManagerCategoryProductTransfer);

            $categoryProducts[] = $gooleTagManagerCategoryProductTransfer->toArray();
        }

        $variables = [
            GoogleTagManagerConstants::CATEGORY_ID => $category['id_category'],
            GoogleTagManagerConstants::CATEGORY_NAME => $category['name'],
            GoogleTagManagerConstants::CATEGORY_CONTENT_TYPE => $contentType,
            GoogleTagManagerConstants::CATEGORY_SIZE => $googleTagManagerCategoryTransfer->getCategoryProducts()->count(),
            GoogleTagManagerConstants::CATEGORY_PRODUCTS => $categoryProducts,
            GoogleTagManagerConstants::PRODUCTS => $googleTagManagerCategoryTransfer->getProducts(),
        ];

        return $this->executePlugins($variables, $googleTagManagerCategoryTransfer);*/

        return $this->stripEmptyArrayIndex($googleTagManagerCategoryTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer
     */
    protected function createGooleTagManagerCategoryTransfer(): GooleTagManagerCategoryTransfer
    {
        return new GooleTagManagerCategoryTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|array $product
     *
     * @return string
     */
    protected function getProductName(array $product): string
    {
        if (!array_key_exists('attributes', $product)) {
            return $product['abstract_name'];
        }

        if (isset($product['attributes'][GoogleTagManagerConstants::NAME_UNTRANSLATED])) {
            return $product['attributes'][GoogleTagManagerConstants::NAME_UNTRANSLATED];
        }

        return $product['abstract_name'];
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
