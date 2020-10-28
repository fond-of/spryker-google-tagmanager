<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\QuoteVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionFieldProductsSkuPlugin extends AbstractPlugin implements QuoteFieldPluginInterface
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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $googleTagManagerTransactionTransfer->addTransactionProductsSkus($itemTransfer->getSku());
        }

        return $googleTagManagerTransactionTransfer;
    }
}
