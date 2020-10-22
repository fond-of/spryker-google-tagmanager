<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface TransactionProductsVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    public function getProduct(ItemTransfer $itemTransfer): GooleTagManagerTransactionProductTransfer;
}
