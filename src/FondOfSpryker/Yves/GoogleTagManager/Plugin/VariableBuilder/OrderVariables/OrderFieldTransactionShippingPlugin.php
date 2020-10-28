<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\OrderFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class OrderFieldTransactionShippingPlugin extends AbstractPlugin implements OrderFieldPluginInterface
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
        $shippingMethods = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $shippingMethods[] = $itemTransfer->getShipment()->getMethod()->getName();
        }

        $googleTagManagerTransactionTransfer->setTransactionShipping(implode(',', $shippingMethods));

        return $googleTagManagerTransactionTransfer;
    }
}
