<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionFieldTotalWithoutShippingAmountPlugin extends AbstractPlugin implements QuoteFieldPluginInterface
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
        $moneyPlugin = $this->getFactory()->getMoneyPlugin();

        $gooleTagManagerQuoteTransfer->setTransactionTotalWithoutShippingAmount(
            $moneyPlugin->convertIntegerToDecimal($this->calculateTotalWithoutShipment($quoteTransfer))
        );

        return $gooleTagManagerQuoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculateTotalWithoutShipment(QuoteTransfer $quoteTransfer): int
    {
        if ($quoteTransfer->getTotals() === null) {
            return 0;
        }

        if ($quoteTransfer->getTotals()->getSubtotal() === null) {
            return 0;
        }

        return $quoteTransfer->getTotals()->getSubtotal();
    }
}
