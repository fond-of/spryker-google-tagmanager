<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionFieldTaxPlugin extends AbstractPlugin implements QuoteFieldPluginInterface
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

        $googleTagManagerTransactionTransfer->setTransactionTax($moneyPlugin->convertIntegerToDecimal(
            $quoteTransfer->getTotals()->getTaxTotal()->getAmount()
        ));

        return $googleTagManagerTransactionTransfer;
    }
}
