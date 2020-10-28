<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface TransactionProductsVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function getProduct(ItemTransfer $itemTransfer): GoogleTagManagerTransactionProductTransfer;
}
