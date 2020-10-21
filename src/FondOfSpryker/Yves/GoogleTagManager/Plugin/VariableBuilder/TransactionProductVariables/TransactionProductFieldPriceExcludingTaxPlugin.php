<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldPriceExcludingTaxPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    use LoggerTrait;

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
        try {
            $moneyPlugin = $this->getFactory()->getMoneyPlugin();

            if ($itemTransfer->getUnitPrice()) {
                return $gooleTagManagerTransactionProductTransfer->setPriceExcludingTax(
                    $moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitPrice())
                );
            }

            $priceExcludingTax = $itemTransfer->getUnitPrice() - $itemTransfer->getUnitTaxAmount();

            $gooleTagManagerTransactionProductTransfer->setPriceExcludingTax(
                $moneyPlugin->convertIntegerToDecimal($priceExcludingTax)
            );
        } catch (Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute/s [%s, %s] not found in %s',
                $itemTransfer::UNIT_PRICE,
                $itemTransfer::UNIT_TAX_AMOUNT,
                self::class
            ), ['quote' => json_encode($itemTransfer)]);
        }

        return $gooleTagManagerTransactionProductTransfer;
    }
}
