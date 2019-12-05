<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class OrderDiscountPlugin implements OrderVariableBuilderPluginInterface
{
    public const FIELD_VOUCHER_CODE = 'voucherCode';
    public const FIELD_DISCOUNT_TOTAL = 'discountTotal';

    /**
     * @return string
     */
    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $variables
     *
     * @return array
     */
    public function handle(OrderTransfer $orderTransfer, array $variables): array
    {
        $result = [];

        if ($orderTransfer->getTotals() instanceof TotalsTransfer && $orderTransfer->getTotals()->getDiscountTotal() > 0) {
            $result[static::FIELD_DISCOUNT_TOTAL] = $orderTransfer->getTotals()->getDiscountTotal()/100;
        }

        if ($orderTransfer->getCalculatedDiscounts()->count() > 0) {
            /** @var CalculatedDiscountTransfer $discountTotalTransfer */
            foreach ($orderTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer)  {
                $result[static::FIELD_VOUCHER_CODE] = $calculatedDiscountTransfer->getVoucherCode();

                break; // we only accept one voucher
            }
        }

        return $result;
    }
}
