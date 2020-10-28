<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldEntityPlugin implements QuoteFieldPluginInterface
{
    public const TRANSACTION_ENTITY_QUOTE = 'QUOTE';

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer
     */
    public function handle(
        GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer,
        QuoteTransfer $quoteTransfer,
        array $params = []
    ): GoogleTagManagerTransactionTransfer {
        return $googleTagManagerTransactionTransfer->setTransactionEntity(static::TRANSACTION_ENTITY_QUOTE);
    }
}
