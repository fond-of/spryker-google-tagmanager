<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldCustomerEmailPlugin implements QuoteFieldPluginInterface
{
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
        return $googleTagManagerTransactionTransfer->setCustomerEmail($this->getCustomerEmailFromQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCustomerEmailFromQuote(QuoteTransfer $quoteTransfer): string
    {
        $addressTransfer = $quoteTransfer->getBillingAddress();

        if ($addressTransfer === null || !$addressTransfer->getEmail()) {
            return '';
        }

        return $addressTransfer->getEmail();
    }
}
