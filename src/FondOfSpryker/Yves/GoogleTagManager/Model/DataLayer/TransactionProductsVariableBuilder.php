<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\QuantityPlugin;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class TransactionProductsVariableBuilder implements TransactionProductsVariableBuilderInterface
{
    /**
     * @var array|\FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\TransactionProductVariableBuilderPluginInterface[]
     */
    protected $plugins;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface $storageClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductImageStorageClientInterface $productImageStorageClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\TransactionProductVariableBuilderPluginInterface[] $plugins
     * @param string $locale
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        GoogleTagManagerToProductStorageClientInterface $storageClient,
        GoogleTagManagerToProductImageStorageClientInterface $productImageStorageClient,
        array $plugins,
        string $locale
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->storageClient = $storageClient;
        $this->productImageStorageClient = $productImageStorageClient;
        $this->plugins = $plugins;
        $this->locale = $locale;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $products
     *
     * @return array
     */
    public function getProductsFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $products = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $product = $this->getDefaultVariables($itemTransfer);
            $product = \array_merge($product, $this->executePlugins($itemTransfer, ['locale' => $this->locale]));

            $products[$itemTransfer->getSku()] = $product;
        }

        return \array_values($products);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getProductsFromOrder(OrderTransfer $orderTransfer): array
    {
        $products = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (isset($products[$itemTransfer->getSku()])) {
                $products[$itemTransfer->getSku()][QuantityPlugin::FIELD_NAME]++;

                continue;
            }

            $this->addAbstractAttributesToItemTransfer($itemTransfer);
            $this->addImagesToItemTransfer($itemTransfer);

            $product = $this->getDefaultVariables($itemTransfer);
            $product = \array_merge($product, $this->executePlugins($itemTransfer, ['locale' => $this->locale]));
            $products[$itemTransfer->getSku()] = $product;
        }

        return \array_values($products);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getDefaultVariables(ItemTransfer $itemTransfer): array
    {
        return [
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_ID => $itemTransfer->getIdProductAbstract(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_SKU => $itemTransfer->getSku(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_PRICE => $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitPrice()),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_PRICE_EXCLUDING_TAX => $this->getPriceExcludingTax($itemTransfer),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_TAX => $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitTaxAmount()),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_TAX_RATE => $itemTransfer->getTaxRate(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function executePlugins(ItemTransfer $itemTransfer)
    {
        $product = [];

        foreach ($this->plugins as $plugin) {
            $product = \array_merge($product, $plugin->handle($itemTransfer, ['locale' => $this->locale]));
        }

        return $product;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float|null
     */
    protected function getPriceExcludingTax(ItemTransfer $itemTransfer): ?float
    {
        if ($itemTransfer->getUnitNetPrice()) {
            return $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitNetPrice());
        }

        return $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitPrice() - $itemTransfer->getUnitTaxAmount());
    }

    /**
     * After checkout ItemTransfer (in OrderTransfer->getItems()) are incomplete. So we need du gather all
     * nesscary informations manually.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addAbstractAttributesToItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (\count($itemTransfer->getAbstractAttributes()) === 0) {
            $productDataAbstract = $this->storageClient->findProductAbstractStorageData(
                $itemTransfer->getIdProductAbstract(),
                $this->locale
            );

            if (!isset($productDataAbstract)) {
                return $itemTransfer;
            }

            return $itemTransfer->setAbstractAttributes([
                $this->locale => $productDataAbstract['attributes'],
            ]);
        }
    }

    /**
     * After checkout ItemTransfer (OrderTransfer->getItems()) are incomplete. So we need du gather all
     * nesscary informations manually.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addImagesToItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        if ($itemTransfer->getImages()->count() === 0) {
            $productAbstractImageStorageTransfer = $this->productImageStorageClient->getProductAbstractImageStorageReader()
                ->findProductImageAbstractStorageTransfer($itemTransfer->getIdProductAbstract(), $this->locale);

            foreach ($productAbstractImageStorageTransfer->getImageSets() as $imageSetStorageTransfer) {
                foreach ($imageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
                    $productImageTransfer = (new ProductImageTransfer)->fromArray($productImageStorageTransfer->toArray());

                    $itemTransfer->addImage($productImageTransfer);
                }
            }
        }

        return $itemTransfer;
    }
}
