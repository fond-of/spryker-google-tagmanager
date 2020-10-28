<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer
     */
    public function handle(
        GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer,
        OrderTransfer $orderTransfer,
        array $params = []
    ): GoogleTagManagerTransactionTransfer;
}
