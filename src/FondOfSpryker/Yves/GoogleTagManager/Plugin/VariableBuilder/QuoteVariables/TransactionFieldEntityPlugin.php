<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldEntityPlugin implements QuoteFieldVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer
     */
    public function handle(
        GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer,
        QuoteTransfer $quoteTransfer,
        array $params = []
    ): GooleTagManagerQuoteTransfer {
        $gooleTagManagerQuoteTransfer->setTransactionEntity('QUOTE');

        return $gooleTagManagerQuoteTransfer;
    }
}
