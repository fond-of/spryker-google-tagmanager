<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhencedEcommercePurchasePlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-purchase.twig';
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @throws
     *
     * @return string
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $params['order'];
        $products = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (isset($products[$itemTransfer->getSku()])) {
                continue;
            }

            $productDataAbstract = $this->getFactory()
                ->getProductStorageClient()
                ->findProductAbstractStorageData($itemTransfer->getIdProductAbstract(), 'en_US');

            $productViewTransfer = (new ProductViewTransfer())->fromArray($productDataAbstract, true);
            $productViewTransfer->setPrice($itemTransfer->getUnitPrice());
            $productViewTransfer->setQuantity($itemTransfer->getQuantity());

            $products[$itemTransfer->getSku()] = $this->getFactory()
                ->createEnhancedEcommerceProductMapperPlugin()
                ->map($productViewTransfer)->toArray();
        }

        return $twig->render($this->getTemplate(), [
            'order' => $orderTransfer,
            'products' => \array_values($products),
            'voucherCode' => $this->getDiscountCode($orderTransfer),
            //'shipment' => $this->getShipment($quoteTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws
     *
     * @return string
     */
    protected function getDiscountCode(OrderTransfer $orderTransfer): string
    {
        $voucherCodes = [];

        foreach ($orderTransfer->getCalculatedDiscounts() as $discountTransfer) {
            array_push($voucherCodes, $discountTransfer->getVoucherCode());
        }

        return \implode(",", $voucherCodes);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipment(OrderTransfer $quoteTransfer): int
    {
        if ($quoteTransfer->getShipment() === null) {
            return 0;
        }

        if ($quoteTransfer->getShipment()->getMethod() === null) {
            return 0;
        }

        return $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
    }
}
