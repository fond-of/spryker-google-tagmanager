<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\GooleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class CategoryVariableBuilder
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables\CategoryVariableBuilderPluginInterface[]
     */
    protected $categoryVariableBuilderPlugins;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param array $categoryVariableBuilderPlugins
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        array $categoryVariableBuilderPlugins = []
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->categoryVariableBuilderPlugins = $categoryVariableBuilderPlugins;
    }

    /**
     * @param array $category
     * @param array $products
     *
     * @return array
     */
    public function getVariables(array $category, array $products): array
    {
        $categoryProducts = [];
        $productSkus = [];

        $googleTagManagerCategoryTransfer = new GooleTagManagerCategoryTransfer();
        $googleTagManagerCategoryTransfer->setIdCategory($category['id_category']);
        $googleTagManagerCategoryTransfer->setName($category['name']);
        $googleTagManagerCategoryTransfer->setSize(\count($products));

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
            GoogleTagManagerConstants::CATEGORY_SIZE => $googleTagManagerCategoryTransfer->getCategoryProducts()->count(),
            GoogleTagManagerConstants::CATEGORY_PRODUCTS => $categoryProducts,
            GoogleTagManagerConstants::PRODUCTS => $googleTagManagerCategoryTransfer->getProducts(),
        ];

        return $this->executePlugins($variables, $googleTagManagerCategoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|array $product
     *
     * @return string
     */
    protected function getProductName(array $product): string
    {
        if (!\array_key_exists('attributes', $product)) {
            return $product['abstract_name'];
        }

        if (isset($product['attributes']['name_untranslated'])) {
            return $product['attributes']['name_untranslated'];
        }

        return $product['abstract_name'];
    }

    /**
     * @param array $variables
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     *
     * @return array
     */
    protected function executePlugins(array $variables, GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer): array
    {
        foreach ($this->categoryVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($gooleTagManagerCategoryTransfer));
        }

        return $variables;
    }
}
