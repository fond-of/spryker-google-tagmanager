<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface TransactionProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function handle(
        GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GoogleTagManagerTransactionProductTransfer;
}
