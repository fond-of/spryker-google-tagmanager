<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\GoogleTagManagerCategoryProductTransfer;
use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;
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
     * @param string $contentType
     *
     * @return array
     */
    public function getVariables(array $category, array $products, string $contentType): array
    {
        $categoryProducts = [];

        $googleTagManagerCategoryTransfer = new GoogleTagManagerCategoryTransfer();
        $googleTagManagerCategoryTransfer->setIdCategory($category['id_category']);
        $googleTagManagerCategoryTransfer->setName($category['name']);
        $googleTagManagerCategoryTransfer->setSize(\count($products));

        foreach ($products as $product) {
            $googleTagManagerCategoryProductTransfer = new GoogleTagManagerCategoryProductTransfer();
            $googleTagManagerCategoryProductTransfer->setIdProductAbstract($product['id_product_abstract']);
            $googleTagManagerCategoryProductTransfer->setName($this->getProductName($product));
            $googleTagManagerCategoryProductTransfer->setSku($product['abstract_sku']);
            $googleTagManagerCategoryProductTransfer->setPrice($this->moneyPlugin->convertIntegerToDecimal($product['price']));

            $googleTagManagerCategoryTransfer->addCategoryProducts($googleTagManagerCategoryProductTransfer);

            $categoryProducts[] = $googleTagManagerCategoryProductTransfer->toArray();
        }

        $variables = [
            GoogleTagManagerConstants::CATEGORY_ID => $category['id_category'],
            GoogleTagManagerConstants::CATEGORY_NAME => $category['name'],
            GoogleTagManagerConstants::CATEGORY_CONTENT_TYPE => $contentType,
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

        if (isset($product['attributes'][GoogleTagManagerConstants::NAME_UNTRANSLATED])) {
            return $product['attributes'][GoogleTagManagerConstants::NAME_UNTRANSLATED];
        }

        return $product['abstract_name'];
    }

    /**
     * @param array $variables
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer
     *
     * @return array
     */
    protected function executePlugins(array $variables, GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer): array
    {
        foreach ($this->categoryVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($googleTagManagerCategoryTransfer));
        }

        return $variables;
    }
}
