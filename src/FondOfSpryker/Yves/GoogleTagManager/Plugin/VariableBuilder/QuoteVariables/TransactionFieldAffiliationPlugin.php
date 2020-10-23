<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldAffiliationPlugin implements QuoteFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer
     */
    public function handle(
        GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer,
        QuoteTransfer $quoteTransfer,
        array $params = []
    ): GooleTagManagerTransactionTransfer {
        return $gooleTagManagerTransactionTransfer->setTransactionAffiliation($quoteTransfer->getStore()->getName());
    }
}
