<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionFieldTotalWithoutShippingAmountPlugin extends AbstractPlugin implements QuoteFieldPluginInterface
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
        $moneyPlugin = $this->getFactory()->getMoneyPlugin();

        $gooleTagManagerTransactionTransfer->setTransactionTotalWithoutShippingAmount(
            $moneyPlugin->convertIntegerToDecimal($this->calculateTotalWithoutShipment($quoteTransfer))
        );

        return $gooleTagManagerTransactionTransfer;
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