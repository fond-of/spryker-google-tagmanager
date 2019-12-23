<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class QuoteVariableBuilder
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[]
     */
    protected $quoteVariableBuilderPlugins;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[] $quoteVariableBuilderPlugins
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        array $quoteVariableBuilderPlugins = []
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->quoteVariableBuilderPlugins = $quoteVariableBuilderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sessionId
     *
     * @return array
     */
    public function getVariables(QuoteTransfer $quoteTransfer, string $sessionId): array
    {
        $variables = [
            GoogleTagManagerConstants::TRANSACTION_ENTITY => GoogleTagManagerConstants::TRANSACTION_ENTITY_QUOTE,
            GoogleTagManagerConstants::TRANSACTION_ID => $sessionId,
            GoogleTagManagerConstants::TRANSACTION_AFFILIATION => $quoteTransfer->getStore()->getName(),
            GoogleTagManagerConstants::TRANSACTION_TOTAL => $this->moneyPlugin->convertIntegerToDecimal(
                $quoteTransfer->getTotals()->getGrandTotal()
            ),
            GoogleTagManagerConstants::TRANSACTION_WITHOUT_SHIPPING_AMOUNT => $this->moneyPlugin->convertIntegerToDecimal(
                $this->getTotalWithoutShippingAmount($quoteTransfer)
            ),
            GoogleTagManagerConstants::TRANSACTION_TAX => $this->moneyPlugin->convertIntegerToDecimal(
                $quoteTransfer->getTotals()->getTaxTotal()->getAmount()
            ),
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS => $this->getTransactionProducts($quoteTransfer),
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS_SKUS => $this->getTransactionProductsSkus($quoteTransfer),
            GoogleTagManagerConstants::CUSTOMER_EMAIL => $this->getCustomerEmail($quoteTransfer->getBillingAddress()),
        ];

        return $this->executePlugins($quoteTransfer, $variables);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $variables
     *
     * @return array
     */
    protected function executePlugins(QuoteTransfer $quoteTransfer, array $variables): array
    {
        foreach ($this->quoteVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($quoteTransfer, $variables));
        }

        return $variables;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getTransactionProductsSkus(QuoteTransfer $quoteTransfer): array
    {
        $collection = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $collection[] = $item->getSku();
        }

        return $collection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getTransactionProducts(QuoteTransfer $quoteTransfer): array
    {
        $collection = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $collection[] = $this->getProductForTransaction($item);
        }

        return $collection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return array
     */
    protected function getProductForTransaction(ItemTransfer $product): array
    {
        return [
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_ID => $product->getIdProductAbstract(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_SKU => $product->getSku(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_NAME => $this->getProductName($product),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_PRICE => $this->moneyPlugin->convertIntegerToDecimal($product->getUnitPrice()),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_PRICE_EXCLUDING_TAX => $this->getPriceExcludingTax($product),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_TAX => $this->moneyPlugin->convertIntegerToDecimal($product->getUnitTaxAmount()),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_TAX_RATE => $product->getTaxRate(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCT_QUANTITY => $product->getQuantity(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return float|null
     */
    protected function getPriceExcludingTax(ItemTransfer $product): ?float
    {
        if ($product->getUnitNetPrice()) {
            return $this->moneyPlugin->convertIntegerToDecimal($product->getUnitNetPrice());
        }

        return $this->moneyPlugin->convertIntegerToDecimal($product->getUnitPrice() - $product->getUnitTaxAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return string
     */
    protected function getCustomerEmail(?AddressTransfer $addressTransfer): string
    {
        if ($addressTransfer === null) {
            return '';
        }

        if (!$addressTransfer->getEmail()) {
            return '';
        }

        return $addressTransfer->getEmail();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return string
     */
    protected function getProductName(ItemTransfer $product): string
    {
        if (!array_key_exists(GoogleTagManagerConstants::NAME_UNTRANSLATED, $product->getConcreteAttributes())) {
            return $product->getName();
        }

        if (!$product->getConcreteAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED]) {
            return $product->getName();
        }

        return $product->getConcreteAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getTotalWithoutShippingAmount(QuoteTransfer $quoteTransfer): int
    {
        if ($quoteTransfer->getShipment()) {
            return $quoteTransfer->getTotals()->getGrandTotal() - $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        }

        return 0;
    }
}
