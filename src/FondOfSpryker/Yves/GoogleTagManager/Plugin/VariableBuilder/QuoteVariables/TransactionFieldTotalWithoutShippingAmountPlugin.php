<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionFieldTotalWithoutShippingAmountPlugin extends AbstractPlugin implements QuoteFieldPluginInterface
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
        $moneyPlugin = $this->getFactory()->getMoneyPlugin();

        $googleTagManagerTransactionTransfer->setTransactionTotalWithoutShippingAmount(
            $moneyPlugin->convertIntegerToDecimal($this->calculateTotalWithoutShipment($quoteTransfer))
        );

        return $googleTagManagerTransactionTransfer;
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
