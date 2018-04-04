<?php

/**
 * Google Tag Manager Data Layer Variables
 *
 * @author      Jozsef Geng <jozsef.geng@fondof.de>
 */
namespace FondOfSpryker\Yves\GoogleTagManager\DataLayer;

use Spryker\Yves\Money\Plugin\MoneyPlugin;


class Variable implements VariableInterface
{
    const PAGE_TYPE_PRODUCT  = "product";
    const PAGE_TYPE_HOME  = "home";
    const PAGE_TYPE_CATEGORY  = "category";
    const PAGE_TYPE_CART  = "cart";
    const PAGE_TYPE_ORDER = "order";
    const PAGE_TYPE_OTHER = "other";

    const TRANSACTION_ENTITY_QUOTE = 'QUOTE';
    const TRANSACTION_ENTITY_ORDER = 'ORDER';

    public function getDefaultVariables($page)
    {
        return array(
            'pageType' => $page
        );
    }

    /**
     * @param $product
     * @return mixed|void
     */
    public function getProductVariables($product)
    {
        return array(
            'productId' => $product->getIdProductAbstract(),
            'productName' => $product->getName(),
            'productSku' => $product->getSku(),
            'productPrice' => '',
            'productPriceExcludingTax' => '',
            'productTax' => '',
            'productTaxRate' => ''
        );
    }

    /**
     * @param $category
     * @param $products
     * @return mixed
     */
    public function getCategoryVariables($category, $products)
    {
        $categoryProducts = [];
        $productSkus = [];

        foreach ($products as $product) {
            $productSkus = $product['abstract_sku'];
            $categoryProducts [] = array(
                'id' => $product['id_product_abstract'],
                'name' => $product['abstract_name'],
                'sku' => $product['abstract_sku'],
                'price' => $this->formatPrice($product['price'])
            );


        }

        return array(
            'categoryId' => $category['id_category'],
            'categoryName' => $category['name'],
            'categorySize' => count($categoryProducts),
            'categoryProducts' => $categoryProducts,
            'products' => $productSkus
        );
    }

    /**
     * @param $quoteTransfer
     * @return array|mixed
     */
    public function getQuoteVariables($quoteTransfer)
    {
        $transactionProducts = [];
        $transactionProductsSkus = [];
        $quoteItems = $quoteTransfer->getItems();

        if (count($quoteItems) > 0) {
            foreach ($quoteItems as $item) {
                $transactionProductsSkus[] = $item->getSku() ;
                $transactionProducts [] = array(
                    'id' => $item->getIdProductAbstract(),
                    'sku' => $item->getSku(),
                    'name' => $item->getName(),
                    'price' => $this->formatPrice($item->getUnitPrice()),
                    'priceexcludingtax' => $this->formatPrice($item->getUnitNetPrice()),
                    'tax' => $this->formatPrice($item->getUnitTaxAmount()),
                    'taxrate' => $item->getTaxRate()
                );
            }
        }

        return  array(
            'transactionEntity' => self::TRANSACTION_ENTITY_QUOTE,
            'transactionId' => '',
            'transactionAffiliation' => $quoteTransfer->getStore()->getName(),
            'transactionTotal' => $this->formatPrice($quoteTransfer->getTotals()->getGrandTotal()),
            'transactionTotalWithoutShippingAmount' => '',
            'transactionTax' => $this->formatPrice($quoteTransfer->getTotals()->getTaxTotal()->getAmount()),
            'transactionProducts' => $transactionProducts,
            'transactionProductsSkus' => $transactionProductsSkus
        );
    }

    /**
     * @param $quoteTransfer
     * @return array
     */
    public function getOrderVariables($quoteTransfer)
    {
        $transactionProducts = [];
        $transactionProductsSkus = [];
        $orderItems = $quoteTransfer->getItems();

        if (count($orderItems) > 0) {
            foreach ($orderItems as $item) {
                $transactionProductsSkus[] = $item->getSku() ;
                $transactionProducts [] = array(
                    'id' => $item->getIdProductAbstract(),
                    'sku' => $item->getSku(),
                    'name' => $item->getName(),
                    'price' => $this->formatPrice($item->getUnitPrice()),
                    'priceexcludingtax' => $this->formatPrice($item->getUnitNetPrice()),
                    'tax' => $this->formatPrice($item->getUnitTaxAmount()),
                    'taxrate' => $item->getTaxRate()
                );
            }
        }

        return  array(
            'transactionEntity' => self::TRANSACTION_ENTITY_ORDER,
            'transactionId' => $quoteTransfer->getOrderReference(),
            //'transactionDate' => $quoteTransfer->getCreatedAt(),
            'transactionAffiliation' => $quoteTransfer->getStore()->getName(),
            'transactionTotal' => $this->formatPrice($quoteTransfer->getTotals()->getGrandTotal()),
            'transactionTotalWithoutShippingAmount' => '',
            'transactionSubtotal' => '',
            'transactionTax' => $this->formatPrice($quoteTransfer->getTotals()->getTaxTotal()->getAmount()),
            'transactionShipping' => $quoteTransfer->getShipment()->getMethod()->getName(),
            'transactionPayment' => $quoteTransfer->getPayment()->getPaymentMethod(),
            'transactionCurrency' => $quoteTransfer->getCurrency(),
            'transactionProducts' => $transactionProducts,
            'transactionProductsSkus' => $transactionProductsSkus
        );
    }

    /**
     * @param $amount
     * @return float
     */
    private function formatPrice($amount)
    {
        $money = new MoneyPlugin();

        return $money->convertIntegerToDecimal($amount);
    }
}