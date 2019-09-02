<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Shared\Shipment\ShipmentConstants;

class OrderVariableBuilder
{
    public const CUSTOMER_EMAIL = 'customerEmail';

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var array
     */
    protected $orderVariableBuilderPlugins;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\OrderVariableBuilderPluginInterface[] $orderVariableBuilderPlugins
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        array $orderVariableBuilderPlugins = []
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->orderVariableBuilderPlugins = $orderVariableBuilderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getVariables(OrderTransfer $orderTransfer): array
    {
        $variables = [
            GoogleTagManagerConstants::TRANSACTION_ENTITY => strtoupper(GoogleTagManagerConstants::PAGE_TYPE_ORDER),
            GoogleTagManagerConstants::TRANSACTION_ID => $orderTransfer->getOrderReference(),
            GoogleTagManagerConstants::TRANSACTION_DATE => $orderTransfer->getCreatedAt(),
            GoogleTagManagerConstants::TRANSACTION_AFFILIATION => $orderTransfer->getStore(),
            GoogleTagManagerConstants::TRANSACTION_TOTAL => $this->moneyPlugin->convertIntegerToDecimal(
                $orderTransfer->getTotals()->getGrandTotal()
            ),
            GoogleTagManagerConstants::TRANSACTION_WITHOUT_SHIPPING_AMOUNT => $this->moneyPlugin->convertIntegerToDecimal(
                $this->getTransactionTotalWithoutShippingAmount($orderTransfer)
            ),
            GoogleTagManagerConstants::TRANSACTION_SUBTOTAL => $this->moneyPlugin->convertIntegerToDecimal(
                $orderTransfer->getTotals()->getSubtotal()
            ),
            GoogleTagManagerConstants::TRANSACTION_TAX => $this->moneyPlugin->convertIntegerToDecimal(
                $orderTransfer->getTotals()->getTaxTotal()->getAmount()
            ),
            GoogleTagManagerConstants::TRANSACTION_SHIPPING => implode('-', $this->getShipmentMethods($orderTransfer)),
            GoogleTagManagerConstants::TRANSACTION_PAYMENT => implode('-', $this->getPaymentMethods($orderTransfer)),
            GoogleTagManagerConstants::TRANSACTION_CURRENCY => $orderTransfer->getCurrencyIsoCode(),
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS => $this->getTransactionProducts($orderTransfer),
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS_SKUS => $this->getTransactionProductsSkus($orderTransfer),
            GoogleTagManagerConstants::CUSTOMER_EMAIL => $this->getCustomerEmail($orderTransfer->getBillingAddress()),
        ];

        return $this->executePlugins($orderTransfer, $variables);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $variables
     *
     * @return array
     */
    protected function executePlugins(OrderTransfer $orderTransfer, array $variables): array
    {
        foreach ($this->orderVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($orderTransfer, $variables));
        }

        return $variables;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getTransactionProducts(OrderTransfer $orderTransfer): array
    {
        $collection = [];

        foreach ($orderTransfer->getItems() as $item) {
            $collection[] = $this->getProductForTransaction($item);
        }

        return $collection;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getTransactionProductsSkus(OrderTransfer $orderTransfer): array
    {
        $collection = [];

        foreach ($orderTransfer->getItems() as $item) {
            $collection[] = $item->getSku();
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
            GoogleTagManagerConstants::PRODUCT_ID => $product->getIdProductAbstract(),
            GoogleTagManagerConstants::PRODUCT_SKU => $product->getSku(),
            GoogleTagManagerConstants::PRODUCT_NAME => $product->getName(),
            GoogleTagManagerConstants::PRODUCT_PRICE => $this->moneyPlugin->convertIntegerToDecimal($product->getUnitPrice()),
            GoogleTagManagerConstants::PRODUCT_PRICE_EXCLUDING_TAX => $this->getPriceExcludingTax($product),
            GoogleTagManagerConstants::PRODUCT_PRICE_TAX => $this->moneyPlugin->convertIntegerToDecimal($product->getUnitTaxAmount()),
            GoogleTagManagerConstants::PRODUCT_PRICE_TAX_RATE => $product->getTaxRate(),
            GoogleTagManagerConstants::PRODUCT_PRICE_QUANTITY => $product->getQuantity(),
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getShipmentMethods(OrderTransfer $orderTransfer): array
    {
        $shipmentMethods = [];

        foreach ($orderTransfer->getShipmentMethods() as $shipment) {
            $shipmentMethods[] = $shipment->getName();
        }

        return $shipmentMethods;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getPaymentMethods(OrderTransfer $orderTransfer): array
    {
        $paymentMethods = [];

        foreach ($orderTransfer->getPayments() as $payment) {
            $paymentMethods[] = $payment->getPaymentMethod();
        }

        return $paymentMethods;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getExpenses(OrderTransfer $orderTransfer): array
    {
        $expenses = [];

        foreach ($orderTransfer->getExpenses() as $expense) {
            $expenses[$expense->getType()] = (!array_key_exists($expense->getType(), $expenses)) ? $expense->getUnitPrice() : $expenses[$expense->getType()] + $expense->getUnitPrice();
        }

        return $expenses;
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int|null
     */
    protected function getTransactionTotalWithoutShippingAmount(OrderTransfer $orderTransfer): ?int
    {
        $expenses = $this->getExpenses($orderTransfer);

        if (array_key_exists(ShipmentConstants::SHIPMENT_EXPENSE_TYPE, $expenses)) {
            return $orderTransfer->getTotals()->getGrandTotal() - $expenses[ShipmentConstants::SHIPMENT_EXPENSE_TYPE];
        }

        return $orderTransfer->getTotals()->getGrandTotal();
    }
}
