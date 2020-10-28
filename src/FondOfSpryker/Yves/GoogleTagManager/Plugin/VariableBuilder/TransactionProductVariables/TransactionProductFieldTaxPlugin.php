<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldTaxPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
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
        $moneyPlugin = $this->getFactory()->getMoneyPlugin();

        $googleTagManagerTransactionProductTransfer->setTax(
            $moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitTaxAmount())
        );

        return $googleTagManagerTransactionProductTransfer;
    }
}
