<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class OrderFieldTransactionPaymentPlugin extends AbstractPlugin implements OrderFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer
     */
    public function handle(
        GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer,
        OrderTransfer $orderTransfer,
        array $params = []
    ): GooleTagManagerTransactionTransfer {
        $paymentMethods = [];

        foreach ($orderTransfer->getPayments() as $payment) {
            $paymentMethods[] = $payment->getPaymentMethod();
        }

        $gooleTagManagerTransactionTransfer->setTransactionPayment(implode(',', $paymentMethods));

        return $gooleTagManagerTransactionTransfer;
    }
}
