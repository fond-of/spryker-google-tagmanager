<?php

/**
 * Google Tag Manager Data Layer Variables
 *
 * @author      Jozsef Geng <jozsef.geng@fondof.de>
 */

namespace FondOfSpryker\Yves\GoogleTagManager\DataLayer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;

interface VariableInterface
{
    /**
     * @param $product
     * @return mixed
     */
    public function getDefaultVariables($page);

    /**
     * @param Generated\Shared\Transfer\StorageProductTransfer $product
     * @return array
     */
    public function getProductVariables(StorageProductTransfer $product);

    /**
     * @param $category
     * @param $products
     * @return mixed
     */
    public function getCategoryVariables($category, $products);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @return array
     */
    public function getQuoteVariables(QuoteTransfer $quote);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @return array
     */
    public function getOrderVariables(OrderTransfer $orderTransfer);
}
