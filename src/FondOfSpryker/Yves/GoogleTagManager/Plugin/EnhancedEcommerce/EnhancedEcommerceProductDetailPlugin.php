<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
 */
class EnhancedEcommerceProductDetailPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
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
        $productData = $this->getClient()
            ->getProductStorageClient()
            ->findProductAbstractStorageData($params['idProductAbstract'], $this->getLocale());

        $productViewTransfer = $this->getClient()
            ->getProductStorageClient()
            ->mapProductStorageData($productData, $this->getLocale());

        $products[] = $this->getFactory()
            ->getEnhancedEcommerceProductMapperPlugin()
            ->map($productViewTransfer->toArray())->toArray();

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderProductDetail($products),
            ],
        ]);
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function renderProductDetail(array $products): array
    {
        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_DETAIL);
        $enhancedEcommerceTransfer->setEcommerce([
            'detail' => [
                'actionField' => [
                    'list' => 'product detail view',
                ],
                'products' => $products,
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
