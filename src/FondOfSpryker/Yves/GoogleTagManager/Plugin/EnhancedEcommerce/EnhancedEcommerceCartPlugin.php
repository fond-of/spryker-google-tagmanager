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
class EnhancedEcommerceCartPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @return array
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->cartView($request),
                $this->addProduct($request),
                $this->removeProduct($request),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function cartView(Request $request): array
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

        return $this->renderCartView($quoteTransfer, $products);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[] $products
     * @return array
     */
    protected function renderCartView(QuoteTransfer $quoteTransfer, array $products): array
    {
        $content = [
            'event' => 'eec.checkout',
            'ecommerce' => [
                'actionField' => [
                    'step' => 1,
                ],
                'products' => [],
            ],
        ];

        foreach ($quoteTransfer->getItems() as $item) {
            $content['ecommerce']['products'] = [
                'id' => $item->getSku(),
                'name' => $item->getName(),
                'variant' => $products[$item->getSku()]->getAttributes()['style'],
                'brand' => $quoteTransfer->getStore()->getName(),
                'quantity' => $item->getQuantity(),
                'dimension1' => $products[$item->getSku()]->getAttributes()['size'],
            ];
        }

        return $content;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function addProduct(Request $request): array
    {
        if (!$request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD)) {
            return [];
        }

        $addProductEventArray = unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD));

        if (!array_key_exists('event', $addProductEventArray) || $addProductEventArray['event'] !== GoogleTagManagerConstants::EEC_EVENT_ADD) {
            return [];
        }

        $request->getSession()->remove(GoogleTagManagerConstants::EEC_EVENT_ADD);

        return $addProductEventArray;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function removeProduct(Request $request): array
    {
        if (!$request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_REMOVE)) {
            return [];
        }

        $removeProductEventArray = unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_REMOVE));

        if (!array_key_exists('event', $removeProductEventArray) || $removeProductEventArray['event'] !== GoogleTagManagerConstants::EEC_EVENT_REMOVE) {
            return [];
        }

        $request->getSession()->remove(GoogleTagManagerConstants::EEC_EVENT_REMOVE);

        return $removeProductEventArray;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
