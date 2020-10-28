<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldPriceExcludingTaxPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function handle(
        GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GoogleTagManagerTransactionProductTransfer {
        if ($itemTransfer->getUnitPrice() > 0 && $itemTransfer->getUnitTaxAmount() > 0) {
            $moneyPlugin = $this->getFactory()->getMoneyPlugin();
            $priceExcludingTax = $itemTransfer->getUnitPrice() - $itemTransfer->getUnitTaxAmount();

            $googleTagManagerTransactionProductTransfer->setPriceExcludingTax(
                $moneyPlugin->convertIntegerToDecimal($priceExcludingTax)
            );
        }

        return $googleTagManagerTransactionProductTransfer;
    }
}
