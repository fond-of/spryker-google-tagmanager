<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class TransactionFieldCustomerEmailPlugin implements QuoteFieldPluginInterface
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
        return $gooleTagManagerQuoteTransfer->setCustomerEmail($this->getCustomerEmailFromQuote($quoteTransfer));
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
