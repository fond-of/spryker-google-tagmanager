<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper;

use DateTime;
use Exception;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class PriceProductFieldMapperPlugin extends AbstractPlugin implements ProductFieldMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer
     * @param array $params
     *
     * @return void
     */
    public function map(
        ProductViewTransfer $productViewTransfer,
        EnhancedEcommerceProductTransfer $enhancedEcommerceProductTransfer,
        array $params
    ): void {
        if ($this->hasValidSpecialPrice($productViewTransfer)) {
            $specialPrice = $productViewTransfer->getAttributes()['special_price'];

            $specialPrice = $this->getFactory()
                ->getMoneyPlugin()
                ->convertIntegerToDecimal($specialPrice);

            $enhancedEcommerceProductTransfer->setPrice((string)$specialPrice);

            return;
        }

        if (!$productViewTransfer->getPrice()) {
            return;
        }

        $price = $this->getFactory()
            ->getMoneyPlugin()
            ->convertIntegerToDecimal($productViewTransfer->getPrice());

        $enhancedEcommerceProductTransfer->setPrice((string)$price);
    }

    protected function hasValidSpecialPrice(ProductViewTransfer $productViewTransfer): bool
    {
        if (!isset($productViewTransfer->getAttributes()['special_price']) ||
            !isset($productViewTransfer->getAttributes()['special_price_from']) ||
            !\array_key_exists('special_price_to', $productViewTransfer->getAttributes())
        ) {
            return false;
        }

        try {
            $specialPriceFromDate = new DateTime($productViewTransfer->getAttributes()['special_price_from']);
        } catch (Exception $e) {
            return false;
        }

        if ($productViewTransfer->getAttributes()['special_price_to'] !== null) {
            try {
                $specialPriceToDate = new DateTime($productViewTransfer->getAttributes()['special_price_to']);
            } catch (Exception $e) {
                return false;
            }
        }

        $current = new DateTime();

        if ($specialPriceFromDate <= $current &&
            ($productViewTransfer->getAttributes()['special_price_to'] === null || $specialPriceToDate >= $current)
        ) {
            return true;
        }

        return false;
    }

    /*
     *
     *
     * {% set isOffer = (
    data.product.attributes.special_price is defined and data.product.attributes.special_price is not empty and (
        data.product.attributes.special_price_from is defined and
        data.product.attributes.special_price_from|date('Y-m-d') <=  "now"|date('Y-m-d')
    ) and (
        data.product.attributes.special_price_to is defined and
        data.product.attributes.special_price_to|date('Y-m-d') >=  "now"|date('Y-m-d')
    )) ? true : false
%}
     *
     *
     *
     */
}
