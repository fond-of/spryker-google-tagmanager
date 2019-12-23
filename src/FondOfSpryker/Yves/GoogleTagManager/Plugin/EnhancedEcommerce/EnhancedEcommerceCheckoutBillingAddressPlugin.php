<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
 */
class EnhancedEcommerceCheckoutBillingAddressPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-checkout-billing-address.twig';
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
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();
        $products = [];

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
        ]);
    }
}
