<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Exception;
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
        $products = [];
        $voucherCode = '';

        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        foreach ($quoteTransfer->getVoucherDiscounts() as $discount) {
            $voucherCode = $discount->getVoucherCode();
        }

        foreach ($quoteTransfer->getItems() as $item) {
            $productData = $this->getClient()
                ->getProductStorageClient()
                ->findProductAbstractStorageData($item->getIdProductAbstract(), $this->getLocale());

            $products[$item->getSku()] = $this->getClient()->getProductStorageClient()->mapProductStorageData(
                $productData,
                $this->getLocale()
            );
        }

        return $twig->render($this->getTemplate(), [
            'quote' => $quoteTransfer,
            'products' => $products,
            'voucherCode' => $voucherCode,
            'shipment' => $this->getShipment($quoteTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws
     * @throws \Exception
     *
     * @return string
     */
    protected function getDiscoutCode(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getVoucherDiscounts()->count() === 0) {
            return '';
        }

        if ($quoteTransfer->getVoucherDiscounts()->count() > 1) {
            throw new Exception('only no or one discount voucher is supported');
        }

        $discountTransfer = $quoteTransfer->getVoucherDiscounts()[0];

        return $discountTransfer->getVoucherCode();
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
