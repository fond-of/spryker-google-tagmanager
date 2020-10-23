<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldEntityPlugin implements QuoteFieldPluginInterface
{
    public const TRANSACTION_ENTITY_QUOTE = 'QUOTE';

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
        return $gooleTagManagerTransactionTransfer->setTransactionEntity(static::TRANSACTION_ENTITY_QUOTE);
    }
}
