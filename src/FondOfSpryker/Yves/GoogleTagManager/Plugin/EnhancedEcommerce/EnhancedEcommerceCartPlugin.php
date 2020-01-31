<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceCartPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @param Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @return string
     * @throws
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        $sessionHandler = $this->getFactory()->createEnhancedEcommerceSessionHandler();

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderCartView(),
                $sessionHandler->getAddProductEventArray(true),
                $sessionHandler->getChangeProductQuantityEventArray(true),
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductViewTransfer[] $products
     * @return array
     */
    protected function renderCartView(): array
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_CHECKOUT);
        $enhancedEcommerceTransfer->setEcommerce([
            'checkout' => [
                'actionField' => [
                    'step' => GoogleTagManagerConstants::EEC_CHECKOUT_STEP_CART,
                ],
                'products' => $this->renderCartViewProducts($quoteTransfer),
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function renderCartViewProducts(QuoteTransfer $quoteTransfer): array
    {
        $products = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $productData = $this->getFactory()
                ->getProductStorageClient()
                ->findProductAbstractStorageData($item->getIdProductAbstract(), $this->getLocale());

            $products[] = $this->getFactory()
                ->getEnhancedEcommerceProductMapperPlugin()
                ->map(array_merge($productData, ['quantity' => $item->getQuantity()]));
        }

        return $products;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
