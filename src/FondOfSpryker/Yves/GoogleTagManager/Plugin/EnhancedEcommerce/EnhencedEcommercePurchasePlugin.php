<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
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
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $request->getSession()->get(GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE);
        $request->getSession()->remove(GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE);
        $products = [];

        if (!$quoteTransfer instanceof QuoteTransfer) {
            return '';
        }

        foreach ($quoteTransfer->getItems() as $item) {
            $productDataAbstract = $this->getFactory()
                ->getProductStorageClient()
                ->findProductAbstractStorageData($item->getIdProductAbstract(), $this->getLocale());

            $productViewTransfer = $this->getFactory()
                ->getProductStorageClient()
                ->mapProductStorageData($productDataAbstract, $this->getLocale(), []);

            $products[] = $this->getFactory()
                ->getEnhancedEcommerceProductMapperPlugin()
                ->map(array_merge($productViewTransfer->toArray(), ['quantity' => $item->getQuantity()]))->toArray();
        }

        return $twig->render($this->getTemplate(), [
            'order' => $quoteTransfer,
            'products' => $products,
            'voucherCode' => $this->getDiscountCode($quoteTransfer),
            'shipment' => $this->getShipment($quoteTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws
     *
     * @return string
     */
    protected function getDiscountCode(QuoteTransfer $quoteTransfer): string
    {
        $voucherCodes = [];

        foreach ($quoteTransfer->getVoucherDiscounts() as $voucherDiscount) {
            array_push($voucherCodes, $voucherDiscount->getVoucherCode());
        }

        return \implode(",", $voucherCodes);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipment(QuoteTransfer $quoteTransfer): int
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
