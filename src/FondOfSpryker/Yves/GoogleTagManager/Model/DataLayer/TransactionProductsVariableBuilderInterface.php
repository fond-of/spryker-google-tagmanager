<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface TransactionProductsVariableBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getProductsFromQuote(QuoteTransfer $quoteTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getProductsFromOrder(OrderTransfer $orderTransfer): array;
}
