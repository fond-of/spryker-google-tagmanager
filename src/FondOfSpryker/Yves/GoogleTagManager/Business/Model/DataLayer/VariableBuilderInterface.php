<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface VariableBuilderInterface
{
    /**
     * @param string $page
     *
     * @return mixed
     */
    public function getDefaultVariables($page);

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return array
     */
    public function getProductVariables(ProductViewTransfer $product);

    /**
     * @param array $category
     * @param array $products
     *
     * @return mixed
     */
    public function getCategoryVariables(array $category, array $products);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sessionId
     *
     * @return array
     */
    public function getQuoteVariables(QuoteTransfer $quoteTransfer, string $sessionId);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getOrderVariables(OrderTransfer $orderTransfer);
}
