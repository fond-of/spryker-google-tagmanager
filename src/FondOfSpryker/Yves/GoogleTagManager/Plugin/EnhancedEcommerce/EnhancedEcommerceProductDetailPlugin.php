<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
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

        return $twig->render($this->getTemplate(), [
            'product' => $productViewTransfer,
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-product-detail.twig';
    }
}