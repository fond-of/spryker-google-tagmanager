<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\AddressTransfer;
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
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables\TransactionProductVariableBuilderPluginInterface[]
     */
    protected $transactionProductPlugins;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\TransactionProductsVariableBuilderInterface
     */
    protected $transactionProductsVariableBuilder;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\QuoteVariableBuilderPluginInterface[] $quoteVariableBuilderPlugins
     * @param \FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer\TransactionProductsVariableBuilderInterface $transactionProductsVariableBuilder
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        array $quoteVariableBuilderPlugins,
        TransactionProductsVariableBuilderInterface $transactionProductsVariableBuilder
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->quoteVariableBuilderPlugins = $quoteVariableBuilderPlugins;
        $this->transactionProductsVariableBuilder = $transactionProductsVariableBuilder;
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
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS => $this->transactionProductsVariableBuilder->getProductsFromQuote($quoteTransfer),
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getTotalWithoutShippingAmount(QuoteTransfer $quoteTransfer): int
    {
        if ($quoteTransfer->getShipment() === null) {
            return 0;
        }

        if ($quoteTransfer->getTotals() === null) {
            return 0;
        }

        if ($quoteTransfer->getShipment()->getMethod() === null) {
            return 0;
        }

        return $quoteTransfer->getTotals()->getGrandTotal() - $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
    }
}
