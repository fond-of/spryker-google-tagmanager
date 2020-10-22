<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductsVariableBuilderPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\QuantityPlugin;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductVariableBuilderPlugin extends AbstractPlugin implements TransactionProductsVariableBuilderPluginInterface
{
    public const VARIABLE_BUILDER_NAME = 'transactionProducts';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    public function getProduct(ItemTransfer $itemTransfer): GooleTagManagerTransactionProductTransfer
    {
        $gooleTagManagerTransactionProductTransfer = $this->createGooleTagManagerTransactionProductTransfer();

        foreach ($this->getFactory()->getTransactionProductVariableBuilderFieldPlugins() as $plugin) {
            try {
                $gooleTagManagerTransactionProductTransfer = $plugin->handle(
                    $gooleTagManagerTransactionProductTransfer,
                    $itemTransfer
                );
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        return $gooleTagManagerTransactionProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    protected function createGooleTagManagerTransactionProductTransfer(): GooleTagManagerTransactionProductTransfer
    {
        return new GooleTagManagerTransactionProductTransfer();
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
            $product = array_merge($product, $this->executePlugins($itemTransfer, ['locale' => $this->locale]));

            $products[$itemTransfer->getSku()] = $product;
        }

        return array_values($products);
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
            $product = array_merge($product, $this->executePlugins($itemTransfer, ['locale' => $this->locale]));
            $products[$itemTransfer->getSku()] = $product;
        }

        return array_values($products);
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
            $product = array_merge($product, $plugin->handle($itemTransfer, ['locale' => $this->locale]));
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
        if (count($itemTransfer->getAbstractAttributes()) === 0) {
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
                    $productImageTransfer = (new ProductImageTransfer())->fromArray($productImageStorageTransfer->toArray());

                    $itemTransfer->addImage($productImageTransfer);
                }
            }
        }

        return $itemTransfer;
    }
}
