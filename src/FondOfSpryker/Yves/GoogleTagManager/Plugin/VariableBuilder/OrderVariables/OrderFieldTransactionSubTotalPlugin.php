<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class OrderFieldTransactionSubTotalPlugin extends AbstractPlugin implements OrderFieldPluginInterface
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
        $googleTagManagerTransactionTransfer->setTransactionSubtotal(
            $this->getFactory()->getMoneyPlugin()->convertIntegerToDecimal(
                $this->getTotalWithoutShipment($orderTransfer)
            )
        );

        return $googleTagManagerTransactionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getTotalWithoutShipment(OrderTransfer $orderTransfer): int
    {
        if ($orderTransfer->getTotals() === null) {
            return 0;
        }

        if ($orderTransfer->getTotals()->getSubtotal() === null) {
            return 0;
        }

        return $orderTransfer->getTotals()->getSubtotal();
    }
}
