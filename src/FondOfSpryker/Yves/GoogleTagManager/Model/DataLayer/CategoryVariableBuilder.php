<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductResourceAliasStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class CategoryVariableBuilder
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var array|\FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\VariableBuilderPluginInterface[]
     */
    protected $categoryVariableBuilderPlugins;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductResourceAliasStorageClientInterface
     */
    protected $aliasStorageClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface
     */
    private $storageClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductResourceAliasStorageClientInterface $aliasStorageClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface $storageClient
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param string|array $locale
     * @param array|string $categoryVariableBuilderPlugins
     */
    public function __construct(
        GoogleTagManagerToProductResourceAliasStorageClientInterface $aliasStorageClient,
        GoogleTagManagerToProductStorageClientInterface $storageClient,
        MoneyPluginInterface $moneyPlugin,
        string $locale,
        array $categoryVariableBuilderPlugins = []
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->categoryVariableBuilderPlugins = $categoryVariableBuilderPlugins;
        $this->locale = $locale;
        $this->aliasStorageClient = $aliasStorageClient;
        $this->storageClient = $storageClient;
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

        foreach ($products as $product) {
            $productData = $this->aliasStorageClient
                ->findProductAbstractStorageDataBySku($product['abstract_sku'], $this->locale);

            if ($productData === null) {
                continue;
            }

            $product = $this->storageClient
                ->mapProductStorageData($productData, $this->locale);

            $categoryProducts[] = [
                GoogleTagManagerConstants::TRANSACTION_PRODUCT_ID => $product->getIdProductAbstract(),
                GoogleTagManagerConstants::TRANSACTION_PRODUCT_NAME => $this->getProductName($product),
                GoogleTagManagerConstants::TRANSACTION_PRODUCT_SKU => $product->getSku(),
                GoogleTagManagerConstants::TRANSACTION_PRODUCT_PRICE => $this->moneyPlugin->convertIntegerToDecimal($product->getPrice()),
            ];

            $productSkus[] = $product->getSku();
        }

        $variables = [
            GoogleTagManagerConstants::CATEGORY_ID => $category['id_category'],
            GoogleTagManagerConstants::CATEGORY_NAME => $category['name'],
            GoogleTagManagerConstants::CATEGORY_SIZE => count($categoryProducts),
            GoogleTagManagerConstants::CATEGORY_PRODUCTS => $categoryProducts,
            GoogleTagManagerConstants::PRODUCTS => $productSkus,
        ];

        return $this->executePlugins($variables, $category, $products);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return string
     */
    protected function getProductName(ProductViewTransfer $product): string
    {
        if (!array_key_exists(GoogleTagManagerConstants::NAME_UNTRANSLATED, $product->getAttributes())) {
            return $product->getName();
        }

        if (!$product->getAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED]) {
            return $product->getName();
        }

        return $product->getAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED];
    }

    /**
     * @param array $variables
     * @param array $category
     * @param array $products
     *
     * @return array
     */
    protected function executePlugins(array $variables, array $category, array $products): array
    {
        foreach ($this->categoryVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($category, $products));
        }

        return $variables;
    }
}
