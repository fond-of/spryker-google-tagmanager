<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface TransactionProductFieldLayerVariableBuilderPluginInterface
{
    /**
     * @param GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer
     * @param ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return GooleTagManagerTransactionProductTransfer
     */
    public function handle(
        GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GooleTagManagerTransactionProductTransfer;
}
