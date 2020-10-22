<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldPriceExcludingTaxPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    public function handle(
        GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GooleTagManagerTransactionProductTransfer {
        if ($itemTransfer->getUnitPrice() > 0 && $itemTransfer->getUnitTaxAmount() > 0) {
            $moneyPlugin = $this->getFactory()->getMoneyPlugin();
            $priceExcludingTax = $itemTransfer->getUnitPrice() - $itemTransfer->getUnitTaxAmount();

            $gooleTagManagerTransactionProductTransfer->setPriceExcludingTax(
                $moneyPlugin->convertIntegerToDecimal($priceExcludingTax)
            );
        }

        return $gooleTagManagerTransactionProductTransfer;
    }
}
