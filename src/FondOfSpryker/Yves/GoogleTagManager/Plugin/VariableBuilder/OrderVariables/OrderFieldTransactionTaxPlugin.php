<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class OrderFieldTransactionTaxPlugin extends AbstractPlugin implements OrderFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer
     */
    public function handle(
        GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer,
        OrderTransfer $orderTransfer,
        array $params = []
    ): GoogleTagManagerTransactionTransfer {
        $googleTagManagerTransactionTransfer->setTransactionTax(
            $this->getFactory()->getMoneyPlugin()->convertIntegerToDecimal(
                $orderTransfer->getTotals()->getTaxTotal()->getAmount()
            )
        );

        return $googleTagManagerTransactionTransfer;
    }
}
